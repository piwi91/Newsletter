<?php

namespace Piwicms\Admin\NewsletterBundle\Controller;

use Piwicms\Admin\NewsletterBundle\Form\MailingListType;
use Piwicms\System\CoreBundle\Entity\MailingList;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Piwicms\Admin\NewsletterBundle\Controller\BaseController;

class MailingListController extends BaseController
{
    public function indexDatatableAction(Request $request)
    {
        /** @var $dataTable \Piwicms\System\CoreBundle\Helper\Datatable */
        $dataTable = $this->get('piwicms.datatable');
        $dataTable->setEntity('PiwicmsSystemCoreBundle:MailingList', 'mailingList');
        $dataTable->setRequest($request);
        $dataTable->setSelectParameters(array(
            'id',
            'name'
        ));
        $dataTable->makeSearch();
        return $dataTable->sendResponse();
    }

    public function indexAction()
    {
        return $this->render(
            'PiwicmsAdminNewsletterBundle:MailingList:index.html.twig'
        );
    }

    public function newModalAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = new MailingList();

        $form = $this->createForm(new MailingListType(), $entity, array (
            'attr' => array (
                'class' => 'form-horizontal'
            ),
            'action' => $this->generateUrl('piwicms_admin_mailinglist_new')
        ));

        if ($request->isMethod('POST')) {
            $form->submit($request);
            if($form->isValid()){
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'newsletterbundle.mailinglist.form.success'
                );

                return $this->redirect($this->generateUrl('piwicms_admin_mailinglist_index'));
            }
        }

        return $this->render(
            'PiwicmsAdminNewsletterBundle:MailingList:ajax/newmodal.html.twig', array (
                'form' => $form->createView()
            )
        );
    }

    public function editModalAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $mailingList = $em->getRepository('PiwicmsSystemCoreBundle:MailingList')->find($id);
        if (!$mailingList) {
            $this->get('session')->getFlashBag()->add(
                'error',
                'newsletterbundle.mailinglist.form.not_found'
            );
            return $this->redirect($this->generateUrl('piwicms_admin_mailinglist_index'));
        }

        $form = $this->createForm(new MailingListType(), $mailingList, array (
            'attr' => array (
                'class' => 'form-horizontal'
            ),
            'action' => $this->generateUrl('piwicms_admin_mailinglist_edit', array('id' => $id))
        ));
        if ($request->isMethod('POST')) {
            $form->submit($request);
            if($form->isValid()){
                $em->persist($mailingList);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'newsletterbundle.mailinglist.form.success'
                );

                return $this->redirect($this->generateUrl('piwicms_admin_mailinglist_index'));
            }
        }

        return $this->render(
            'PiwicmsAdminNewsletterBundle:MailingList:ajax/editmodal.html.twig', array (
                'form' => $form->createView(),
                'title' => $mailingList->getName()
            )
        );
    }

    public function showModalAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var $mailingList MailingList */
        $mailingList = $em->getRepository('PiwicmsSystemCoreBundle:MailingList')->find($id);
        if (!$mailingList) {
            $this->get('session')->getFlashBag()->add(
                'error',
                'newsletterbundle.mailinglist.form.not_found'
            );
            return $this->redirect($this->generateUrl('piwicms_admin_mailinglist_index'));
        }

        $mailingListUsers = $mailingList->getMailingUser();

        $subscribeUrl = $this->container->getParameter('piwicms.base_url') .
            $this->generateUrl('piwicms_client_newsletter_subscribe');
        $unsubscribeUrl = $this->container->getParameter('piwicms.base_url') .
            $this->generateUrl('piwicms_client_newsletter_unsubscribe');

        return $this->render(
            'PiwicmsAdminNewsletterBundle:MailingList:ajax/showmodal.html.twig', array (
                'mailingListUsers' => $mailingListUsers,
                'title' => $mailingList->getName(),
                'subscribeUrl' => $subscribeUrl,
                'unsubscribeUrl' => $unsubscribeUrl
            )
        );
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $mailing = $em->getRepository('PiwicmsSystemCoreBundle:MailingList')->find($id);
        if ($mailing) {
            $em->remove($mailing);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',
                'newsletterbundle.mailinglist.delete.success'
            );
        } else {
            $this->get('session')->getFlashBag()->add(
                'danger',
                'newsletterbundle.mailinglist.delete.error'
            );
        }
        return $this->redirect($this->generateUrl('piwicms_admin_mailinglist_index'));
    }
}