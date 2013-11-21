<?php

namespace Piwicms\Admin\NewsletterBundle\Controller;

use Piwicms\System\CoreBundle\Entity\Mailing;
use Piwicms\System\CoreBundle\Entity\MailingList;
use Piwicms\System\CoreBundle\Entity\MailingUser;
use Piwicms\System\CoreBundle\Entity\MailingBlock;
use Piwicms\System\CoreBundle\Entity\Newsletter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Piwicms\Admin\NewsletterBundle\Form\NewsletterStep1Type;
use Piwicms\Admin\NewsletterBundle\Form\NewsletterStep2Type;
use Piwicms\Admin\NewsletterBundle\Form\NewsletterStep3Type;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use Piwicms\Admin\NewsletterBundle\Controller\BaseController;

class NewsletterController extends BaseController
{
    public function indexDatatableAction(Request $request)
    {
        /** @var $dataTable \Piwicms\System\CoreBundle\Helper\Datatable */
        $dataTable = $this->get('piwicms.datatable');
        $dataTable->setEntity('PiwicmsSystemCoreBundle:Mailing', 'mailing');
        $dataTable->setRequest($request);
        $dataTable->setSelectParameters(array(
            'id',
            'title',
            'datetime',
            'createdBy'
        ));
        $dataTable->makeSearch();
        return $dataTable->sendResponse();
    }

    public function indexAction()
    {
        return $this->render(
            'PiwicmsAdminNewsletterBundle:Newsletter:index.html.twig'
        );
    }

    public function statisticsAction()
    {
        return $this->render(
            'PiwicmsAdminNewsletterBundle:Newsletter:statistics.html.twig'
        );
    }

    public function statisticsHighchartsAction($id)
    {
        /** @var $ajaxResponse Piwicms\System\CoreBundle\AjaxResponse */
        $ajaxResponse = $this->get('piwicms.ajaxresponse');

        $em = $this->getDoctrine()->getManager();

        $mailingStatisticRepository = $em->getRepository('PiwicmsSystemCoreBundle:MailingStatistic');

        $totalReadedNewsletter = $mailingStatisticRepository->findTotalReadedNewsletterByNewsletter($id);
        $readedNewsletterByDate = $mailingStatisticRepository->findReadedNewsletterByNewsletter($id);
        $clickedUrlByUrl = $mailingStatisticRepository->findClickedUrlByNewsletter($id, 'mailingStatistic.url');
        $clickedUrlByDate = $mailingStatisticRepository->findClickedUrlByNewsletter($id, 'date');
        $mailing = $em->getRepository('PiwicmsSystemCoreBundle:Mailing')
            ->find($id);
        $totalSend = $mailing->getCount();

        foreach ($clickedUrlByDate as $row) {
            $_clickedUrlByDate[$row['date']] = $row['amount'];
        }

        foreach ($readedNewsletterByDate as $row) {
            $chart02Serie1[] = (int)$row['amount'];
            $chart02Serie2[] =
                (isset($_clickedUrlByDate[$row['date']]) ?
                    (int)$_clickedUrlByDate[$row['date']]
                    :
                    0
                );
            $chart02Categories[] = $row['date'];
        }

        foreach ($clickedUrlByUrl as $row) {
            $chart03Serie[] = array($row['url'], (int)$row['amount']);
        }

        $chart01 = array (
            'title' => array (
                'text' => 'E-mail statistics'
            ),
            'tooltip' => array (
                'pointformat' => '{series.name}: <b>{point.percentage:.1f}%</b>'
            ),
            'plotOptions' => array (
                'pie' => array (
                    'allowPointSelect' => true,
                    'cursor' => 'pointer',
                    'dataLabels' => array (
                        'enabled' => true,
                        'color' => '#000',
                        'connectorColor' => '#000',
                        'format' => '<b>{point.name}</b>: {point.percentage:.1f} %'
                    )
                )
            ),
            'series' => array (
                array (
                    'type' => 'pie',
                    'data' => array (
                        array (
                            'name' => 'Readed',
                            'y' => (int)$totalReadedNewsletter
                        ),
                        array (
                            'name' => 'Unreaded',
                            'y' => (int)$totalSend - (int)$totalReadedNewsletter
                        ),
                    )
                )
            )
        );

        $chart02 = array (
            'chart' => array (
                'type' => 'column'
            ),
            'title' => array (
                'text' => 'E-mail statistics by day'
            ),
            'tooltip' => array (
                'pointformat' => '{series.name}: <b>{point.percentage:.1f}%</b>'
            ),
            'plotOptions' => array (
                'column' => array (
                    'pointPadding' => 0.2,
                    'borderWidth' => 0
                )
            ),
            'xAxis' => array (
                'categories' => $chart02Categories
            ),
            'yAxis' => array (
                'min' => 0,
                'title' => array (
                    'text' => 'Viewed'
                )
            ),
            'series' => array (
                array (
                    'name' => 'Viewed newsletters',
                    'data' => $chart02Serie1
                ),
                array (
                    'name' => 'Clicked url\'s',
                    'data' => $chart02Serie2
                )
            )
        );

        $chart03 = array (
            'title' => array (
                'text' => 'E-mail statistics'
            ),
            'tooltip' => array (
                'pointformat' => '{series.name}: <b>{point.percentage:.1f}%</b>'
            ),
            'plotOptions' => array (
                'pie' => array (
                    'allowPointSelect' => true,
                    'cursor' => 'pointer',
                    'dataLabels' => array (
                        'enabled' => true,
                        'color' => '#000',
                        'connectorColor' => '#000',
                        'format' => '<b>{point.name}</b>: {point.percentage:.1f} %'
                    )
                )
            ),
            'series' => array (
                array (
                    'type' => 'pie',
                    'data' => $chart03Serie
                )
            )
        );

        $ajaxResponse->setData(
            array(
                'chart01' => $chart01,
                'chart02' => $chart02,
                'chart03' => $chart03
            )
        );
        return $ajaxResponse->sendResponse();
    }

    public function step1Action()
    {
        $form = $this->createForm(new NewsletterStep1Type());
        return $this->render(
            'PiwicmsAdminNewsletterBundle:Newsletter:step1.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    public function step2Action(Request $request)
    {
        $formData = $request->get('piwicms_newsletter_step1');
        $title = $formData['title'];
        $template = $formData['template'];
        $mailingList = $formData['mailinglist'];

        $session = $this->getRequest()->getSession();
        $session->set('title', $title);
        $session->set('template', $template);
        $session->set('mailingList', $mailingList);

        $blocks = $this->getDoctrine()->getRepository('PiwicmsSystemCoreBundle:ViewBlock')->findBy(
            array (
                'view' => $template
            )
        );

        $form = $this->createForm(new NewsletterStep2Type($blocks));
        return $this->render(
            'PiwicmsAdminNewsletterBundle:Newsletter:step2.html.twig',
            array(
                'form' => $form->createView(),
                'blocks' => $blocks,
                'template' => $template
            )
        );
    }

    public function step3Action(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.context')->getToken();
        if(!$user) {
            throw \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException();
        }

        $session = $this->getRequest()->getSession();
        $title = $session->get('title');
        $template = $session->get('template');
        $mailingList = $session->get('mailingList');

        $mailing = new Mailing();
        $mailing->setTitle($title);
        $mailing->setDatetime(new \DateTime());
        $mailing->setStart(new \DateTime());
        $mailing->setCreatedBy($user->getUser()->getUsername());

        $formData = $request->get('piwicms_newsletter_step2');
        $template = $this->getDoctrine()->getRepository('PiwicmsSystemCoreBundle:View')->find($template);
        $templateBlocks = $template->getViewBlock();
        $index = 1;
        while (isset($formData['mailingBlock_' . $index])) {
            $blocks[] = $formData['mailingBlock_' . $index];
            $twigBlocks[$templateBlocks[$index-1]->getName()] = $formData['mailingBlock_' . $index];
            $mailingBlock = new MailingBlock();
            $mailingBlock->setText($formData['mailingBlock_' . $index]);
            $mailingBlock->setMailing($mailing);
            $mailingBlock->setViewBlock($templateBlocks[$index-1]);
            $mailingBlocks[] = $mailingBlock;
            $index++;
        }

        $countEmailaddressess = 0;
        if (is_array($mailingList)) {
            foreach ($mailingList as $row) {
                $_mailingList = $this->getDoctrine()->getRepository('PiwicmsSystemCoreBundle:MailingList')->find($row);
                $_mailingLists[] = $_mailingList;
                $countEmailaddressess += count($_mailingList->getMailingUser());
            }
        } else {
            $_mailingList = $this->getDoctrine()->getRepository('PiwicmsSystemCoreBundle:MailingList')->find($mailingList);
            $_mailingLists[] = $_mailingList;
            $countEmailaddressess += count($_mailingList->getMailingUser());
        }

        $mailing->setMailingList($_mailingLists);
        $mailing->setTemplate($template);
        $mailing->setMailingBlock($mailingBlocks);

        $em->persist($mailing);
        $em->flush();

        $renderedView = $this->renderMailing($template, $twigBlocks, $mailing->getId());

        $session->set('mailingId', $mailing->getId());

        $form = $this->createForm(new NewsletterStep3Type());
        return $this->render(
            'PiwicmsAdminNewsletterBundle:Newsletter:step3.html.twig',
            array(
                'form' => $form->createView(),
                'mail' => $renderedView,
                'mailing' => $mailing,
                'countEmailaddress' => $countEmailaddressess
            )
        );
    }

    public function submitAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $this->getRequest()->getSession();
        $mailingId = $session->get('mailingId');

        /** @var $mailing Mailing */
        $mailing = $this->getDoctrine()->getRepository('PiwicmsSystemCoreBundle:Mailing')->find($mailingId);

        $form = $this->createForm(new NewsletterStep3Type(), $mailing);
        if($request->isMethod('POST')){
            $form->submit($request);
            if($form->isValid()){
                $mailing->setStatus('Planned');

                $em->persist($mailing);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'Newsletter added and scheduled for sending!'
                );
            }
        } else {
            $this->get('session')->getFlashBag()->add(
                'danger',
                'Something went wrong! Please try it again.'
            );
        }

        return $this->redirect($this->generateUrl('piwicms_admin_newsletter_index'));
    }

    public function renderExampleAction(Request $request)
    {
        $template = $request->get('template');
        $blocks = $request->get('blocks');

        $em = $this->getDoctrine()->getManager();
        /** @var $entity View */
        $entity = $em->getRepository('PiwicmsSystemCoreBundle:View')->find($template);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find View entity.');
        }

        $twigBlocks = array();
        foreach ($blocks as $block) {
            $twigBlocks[$block['block']] = $block['html'];
        }

        $renderedView = $this->renderMailing($entity, $twigBlocks);

        $serializer = $this->get('jms_serializer');
        $serializedView = $serializer->serialize(array('view' => $renderedView, 'entity' => $entity), 'json');
        return new Response($serializedView);
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $mailing = $em->getRepository('PiwicmsSystemCoreBundle:Mailing')->find($id);
        if ($mailing) {
            $title = $mailing->getTitle();
            $em->remove($mailing);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',
                'Deleted newsletter \'' . $title . '\'!'
            );
        } else {
            $this->get('session')->getFlashBag()->add(
                'danger',
                'Something went wrong when deleting the newsletter!'
            );
        }
        return $this->redirect($this->generateUrl('piwicms_admin_newsletter_index'));
    }

    public function previewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $mailing = $em->getRepository('PiwicmsSystemCoreBundle:Mailing')->find($id);
        if ($mailing) {
            foreach ($mailing->getMailingBlock() as $block) {
                $twigBlocks[$block->getViewBlock()->getName()] = $block->getText();
            }
            $renderedView = $this->renderMailing($mailing->getTemplate(), $twigBlocks, $mailing->getId());
        } else {
            $renderedView = "Oops... Something went wrong :-(";
        }
        return $this->render(
            'PiwicmsAdminNewsletterBundle:Newsletter:ajax/newsletterPreview.html.twig',
            array(
                'title' => $mailing->getTitle(),
                'view' => $renderedView
            )
        );
    }

    public function sendEmailAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $mailing Mailing */
        $mailing = $em->getRepository('PiwicmsSystemCoreBundle:Mailing')->find($id);
        if (!$mailing) {
            $this->get('session')->getFlashBag()->add(
                'danger',
                'Couldn\'t find newsletter'
            );
            return $this->redirect($this->generateUrl('piwicms_admin_newsletter_index'));
        }

        foreach ($mailing->getMailingBlock() as $block) {
            $twigBlocks[$block->getViewBlock()->getName()] = $block->getText();
        }

        $_mailaddressSend = array();

        $countSend = $mailing->getCount();
        $mailingLists = $mailing->getMailingList();
        /** @var $mailingList MailingList */
        foreach ($mailingLists as $mailingList) {
            $users = $mailingList->getMailingUser();
            /** @var $user MailingUser */
            foreach ($users as $user) {
                $emailaddress = $user->getEmailaddress();
                $trackingId = $user->getId();
                if (!in_array($emailaddress, $_mailaddressSend)) {
                    $_mailaddressSend[] = $emailaddress;
                    $renderedView = $this->renderMailing($mailing->getTemplate(), $twigBlocks, $mailing->getId(), $trackingId);
                    $message = \Swift_Message::newInstance()
                        ->setSubject($mailing->getTitle())
                        ->setFrom($this->container->getParameter('piwicms.email.from.emailaddress'))
                        ->setTo($emailaddress)
                        ->setBody($renderedView, 'text/html')
                    ;
                    $this->get('mailer')->send($message);
                    $countSend++;
                }
            }
        }
        $mailing->setCount($countSend);
        $em->persist($mailing);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'success',
            'E-mails scheduled for sending'
        );
        return $this->redirect($this->generateUrl('piwicms_admin_newsletter_index'));
    }

    protected function renderMailing($template, $blocks, $mailingId = 0, $trackingId = 0)
    {
        $renderedView = $this->renderView($template->getName(), $blocks);
        // Check if there is a url in the text
        if(preg_match_all('/(href=")(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?\"/', $renderedView, $urls)) {
            foreach ($urls[0] as $url) {
                $newUrl = 'href="' . $this->generateStatisticUrl(str_replace('href="', '', $url), $mailingId, $trackingId);
                $renderedView = str_replace(
                    $url,
                    $newUrl,
                    $renderedView
                );
            }
        }
        // Add trackingImage to mailing
        $image = $this->generateTrackingImage($mailingId, $trackingId);
        return $renderedView . $image;
    }
}