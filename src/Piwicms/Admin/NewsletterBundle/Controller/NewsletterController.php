<?php

namespace Piwicms\Admin\NewsletterBundle\Controller;

use Piwicms\System\CoreBundle\Entity\Mailing;
use Piwicms\System\CoreBundle\Entity\MailingList;
use Piwicms\System\CoreBundle\Entity\MailingUser;
use Piwicms\System\CoreBundle\Entity\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Piwicms\Admin\NewsletterBundle\Form\NewsletterStep1Type;
use Piwicms\Admin\NewsletterBundle\Form\NewsletterStep2Type;
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

        $chart02Serie1 = array();
        $chart02Serie2 = array();
        $chart03Serie = array();
        $chart02Categories = array();
        $_clickedUrlByDate = array();

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

    public function step1Action(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($id = $request->get('id')) {
            $newsletterEntity = $em->getRepository('PiwicmsSystemCoreBundle:Mailing')->find($id);
        }
        if (isset($newsletterEntity)) {
            $session = $this->getRequest()->getSession();
            $session->set('mailingId', $id);
        } else {
            $session = $this->getRequest()->getSession();
            $session->remove('mailingId');
            $newsletterEntity = new Mailing();
        }
        $form = $this->createForm(new NewsletterStep1Type(), $newsletterEntity);
        return $this->render(
            'PiwicmsAdminNewsletterBundle:Newsletter:step1.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    public function step2Action(Request $request)
    {
        if (!$request->isMethod('POST')) {
            $this->get('session')->getFlashBag()->add(
                'warning',
                'No data received - Step2'
            );
            return $this->redirect($this->generateUrl('piwicms_admin_newsletter_index'));
        }
        $em = $this->getDoctrine()->getManager();

        $formData = $request->get('piwicms_newsletter_step1');

        $session = $this->getRequest()->getSession();
        $session->set('title', $formData['title']);
        $session->set('template', $formData['template']);
        $session->set('mailingList', $formData['mailinglist']);

        $id = $session->get('mailingId');
        if ($id) {
            /** @var $newsletterEntity Mailing */
            $newsletterEntity = $em->getRepository('PiwicmsSystemCoreBundle:Mailing')->find($id);
            if ($newsletterEntity) {
                if ($newsletterEntity->getTemplate()->getId() != $formData['template']) {
                    $_template = $em->getRepository('PiwicmsSystemCoreBundle:View')->find($formData['template']);
                    $template = $_template->getView();
                } else {
                    $template = $newsletterEntity->getText();
                }
            }
        } else {
            $newsletterEntity = new Mailing();
            /** @var $_template View */
            $_template = $em->getRepository('PiwicmsSystemCoreBundle:View')->find($formData['template']);
            $template = $_template->getView();
        }
        $newsletterEntity->setText($template);

        $form = $this->createForm(new NewsletterStep2Type(), $newsletterEntity, array (
            'attr' => array (
                'class' => 'form-vertical'
            )
        ));
        return $this->render(
            'PiwicmsAdminNewsletterBundle:Newsletter:step2.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    public function submitAction(Request $request)
    {
        if (!$request->isMethod('POST')) {
            $this->get('session')->getFlashBag()->add(
                'warning',
                'No data received - Submit'
            );
            return $this->redirect($this->generateUrl('piwicms_admin_newsletter_index'));
        }

        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.context')->getToken();
        if(!$user) {
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException();
        }

        $session = $this->getRequest()->getSession();
        $title = $session->get('title');
        $template = $session->get('template');
        $mailingList = $session->get('mailingList');

        $id = $session->get('mailingId');
        if ($id) {
            $mailing = $em->getRepository('PiwicmsSystemCoreBundle:Mailing')->find($id);
        } else {
            $mailing = new Mailing();
        }

        $mailing->setTitle($title);
        $mailing->setDatetime(new \DateTime());
        $mailing->setStart(new \DateTime());
        $mailing->setCreatedBy($user->getUser()->getUsername());

        $formData = $request->get('piwicms_newsletter_step2');

        $template = $this->getDoctrine()->getRepository('PiwicmsSystemCoreBundle:View')->find($template);

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

        $mailing->setStatus('Planned');
        $mailing->setMailingList($_mailingLists);
        $mailing->setTemplate($template);
        $mailing->setText($formData['text']);

        $em->persist($mailing);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'success',
            'Newsletter added and scheduled for sending!'
        );

        return $this->redirect($this->generateUrl('piwicms_admin_newsletter_index'));
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
        $mailingUser = new MailingUser();
        $mailingUser->setFirstname('John');
        $mailingUser->setSurname('Doe');
        $mailingUser->setEmailaddress('john@doe.com');
        if ($mailing) {
            $renderedView = $this->renderMailing($mailing->getText(), $mailingUser, $mailing->getId());
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
                    $renderedView = $this->renderMailing($mailing->getText(), $user, $mailing->getId(), $trackingId);
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

    protected function renderMailing($renderedView, MailingUser $user, $mailingId = 0, $trackingId = 0)
    {
        $search = array('%firstname%', '%surname%', '%emailaddress%');
        $replace = array($user->getFirstname(), $user->getSurname(), $user->getEmailaddress());
        $renderedView    = str_replace($search, $replace, $renderedView);
        // Check if there is a url in the text
        if(preg_match_all('/href="(http|https)\:\/\/([^"]+)"/', $renderedView, $matches)) {
            foreach ($matches[0] as $key => $match) {
                $newUrl = $this->generateStatisticUrl($matches[1][$key] . '://' . $matches[2][$key], $mailingId, $trackingId);
                $renderedView = str_replace(
                    $matches[1][$key] . '://' . $matches[2][$key],
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