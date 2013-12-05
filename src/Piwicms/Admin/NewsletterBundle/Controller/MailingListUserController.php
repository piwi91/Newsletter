<?php

namespace Piwicms\Admin\NewsletterBundle\Controller;

use Piwicms\Admin\NewsletterBundle\Form\MailingListType;
use Piwicms\Admin\NewsletterBundle\Form\MailingListUserType;
use Piwicms\System\CoreBundle\Entity\MailingList;
use Piwicms\System\CoreBundle\Entity\MailingUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Piwicms\Admin\NewsletterBundle\Controller\BaseController;

class MailingListUserController extends BaseController
{
    public function indexDatatableAction($id, Request $request)
    {
        /** @var $dataTable \Piwicms\System\CoreBundle\Helper\Datatable */
        $dataTable = $this->get('piwicms.datatable');
        $dataTable->setEntity('PiwicmsSystemCoreBundle:MailingUser', 'mailingUser');
        $dataTable->setRequest($request);
        $dataTable->setSelectParameters(array(
            'id',
            'firstname',
            'surname',
            'emailaddress'
        ));
        $dataTable->setAdvancedWhere(
            function ($qb) use ($id)
            {
                $qb ->andWhere($qb->expr()->eq('mailingList.id', ':id'))
                    ->setParameter('id', $id);
            }
        );
        $dataTable->setAdvancedJoin(
            function ($qb) use ($id)
            {
                $qb ->innerJoin('mailingUser.mailingList', 'mailingList');
            }
        );
        $dataTable->makeSearch();
        return $dataTable->sendResponse();
    }

    public function indexAction($id)
    {
        $subscribeUrl = $this->container->getParameter('piwicms.base_url') .
            $this->generateUrl('piwicms_client_newsletter_subscribe');
        $unsubscribeUrl = $this->container->getParameter('piwicms.base_url') .
            $this->generateUrl('piwicms_client_newsletter_unsubscribe');

        return $this->render(
            'PiwicmsAdminNewsletterBundle:MailingListUser:index.html.twig',
            array (
                'id' => $id,
                'subscribeUrl' => $subscribeUrl,
                'unsubscribeUrl' => $unsubscribeUrl
            )
        );
    }

    public function newModalAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $form = $this->createForm(new MailingListUserType($id), null, array (
            'attr' => array (
                'class' => 'form-horizontal'
            ),
            'action' => $this->generateUrl('piwicms_admin_mailinglist_users_new', array("id" => $id))
        ));

        if ($request->isMethod('POST')) {
            $form->submit($request);
            if($form->isValid()){
                $data = $form->getData();

                $mailingList = $em->getRepository('PiwicmsSystemCoreBundle:MailingList')->find($id);

                if (!empty($data['mailingUser'])) {
                    $entity = $em->getRepository('PiwicmsSystemCoreBundle:MailingUser')->find($data['mailingUser']);
                } else {
                    $entity = new MailingUser();
                    $entity->setFirstname($data['firstname']);
                    $entity->setSurname($data['surname']);
                    $entity->setEmailaddress($data['emailaddress']);
                }
                $entity->addMailingList($mailingList);

                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'newsletterbundle.mailinglist.users.form.success'
                );

                return $this->redirect($this->generateUrl('piwicms_admin_mailinglist_users_index', array("id" => $id)));
            }
        }

        return $this->render(
            'PiwicmsAdminNewsletterBundle:MailingListUser:ajax/newmodal.html.twig', array (
                'form' => $form->createView()
            )
        );
    }

    public function deleteAction($mailingListId, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $mailingUser = $em->getRepository('PiwicmsSystemCoreBundle:MailingUser')->find($id);
        if ($mailingUser) {
            $mailingList = $em->getRepository('PiwicmsSystemCoreBundle:MailingList')->find($mailingListId);
            $mailingUser->removeMailingList($mailingList);

            $em->persist($mailingUser);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',
                'newsletterbundle.mailinglist.users.delete.success'
            );
        } else {
            $this->get('session')->getFlashBag()->add(
                'danger',
                'newsletterbundle.mailinglist.users.delete.error'
            );
        }
        return $this->redirect($this->generateUrl('piwicms_admin_mailinglist_users_index', array("id" => $mailingListId)));
    }
}