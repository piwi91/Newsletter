<?php

namespace Piwicms\Client\NewsletterBundle\Controller;

use Piwicms\Client\NewsletterBundle\Form\UnsubscripeType;
use Piwicms\System\CoreBundle\Entity\MailingUser;
use Piwicms\Client\NewsletterBundle\Form\SubscribeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NewsletterController extends Controller
{
    public function newsletterAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $mailing = $em->getRepository('PiwicmsSystemCoreBundle:Mailing')->findOneBySlug($slug);
        if (!$mailing) {
            return new Response('Couldn\'t find requested newsletter. Please check the URL.');
        }

        foreach ($mailing->getMailingBlock() as $block) {
            $twigBlocks[$block->getViewBlock()->getName()] = $block->getText();
        }

        $renderedView = $this->renderMailing($mailing->getTemplate(), $twigBlocks, $mailing->getId(), 0);

        return new Response($renderedView);
    }

    public function subscribeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $formData = $request->get('subscribe');
        $emailaddress = $formData['emailaddress'];
        $_mailingUser = $em->getRepository('PiwicmsSystemCoreBundle:MailingUser')->findOneByEmailaddress($emailaddress);
        if ($_mailingUser instanceof MailingUser) {
            $mailingUser = $_mailingUser;
            $title = 'client.newsletterbundle.form.subscribe.edit_title';
        } else {
            $mailingUser = new MailingUser();
            $title = 'client.newsletterbundle.form.subscribe.add_title';
        }

        $form = $this->createForm(new SubscribeType(), $mailingUser,
            array(
                "attr" => array (
                    "class" => "form-horizontal"
                )
            )
        );

        if($request->isMethod('POST')){
            $form->submit($request);
            if($form->isValid()){
                $title = 'client.newsletterbundle.form.subscribe.edit_title';
                $em->persist($mailingUser);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'client.newsletterbundle.form.subscribe.success'
                );
            }
        }

        return $this->render('PiwicmsClientNewsletterBundle:Newsletter:subscribe.html.twig' , array (
            'title' => $title,
            'form' => $form->createView()
        ));
    }

    public function unsubscribeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $mailingUser = null;
        if ($request->isMethod('POST')) {
            $formData = $request->get('unsubscribe');
            $mailingUser = $em->getRepository('PiwicmsSystemCoreBundle:MailingUser')->findOneByEmailaddress($formData['emailaddress']);
            if (!$mailingUser) {
                $this->get('session')->getFlashBag()->add(
                    'danger',
                    'client.newsletterbundle.form.unsubscribe.no_user_found'
                );
                return $this->redirect($this->generateUrl('piwicms_client_newsletter_subscribe'));
            }
            $mailingUser->setMailingList(null);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                'client.newsletterbundle.form.unsubscribe.success'
            );

            return $this->redirect($this->generateUrl('piwicms_client_newsletter_subscribe'));
        }

        $form = $this->createForm(new UnsubscripeType(), $mailingUser,
            array(
                "attr" => array (
                    "class" => "form-horizontal"
                )
            )
        );

        return $this->render('PiwicmsClientNewsletterBundle:Newsletter:unsubscribe.html.twig', array(
            'form' => $form->createView()
        ));
    }

    protected function renderMailing($template, $blocks, $mailingId = 0, $trackingId = 0)
    {
        $renderedView = $this->renderView($template->getName(), $blocks);
        return $renderedView;
    }
}
