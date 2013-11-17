<?php

namespace Piwicms\Admin\PrivateMessageBundle\Controller;

use Piwicms\System\CoreBundle\Entity\PrivateMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Piwicms\Admin\PrivateMessageBundle\Form\PrivateMessageType;
use Symfony\Component\HttpFoundation\Response;

class PrivateMessageController extends Controller
{
    public function indexDatatableAction(Request $request)
    {
        $user = $this->get('security.context')->getToken();
        if(!$user) {
            throw \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException();
        }
        $userId = $user->getUser()->getId();

        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('PiwicmsSystemCoreBundle:PrivateMessage');
        $metadata = $em->getClassMetadata('PiwicmsSystemCoreBundle:PrivateMessage');

        /** @var $dataTable \Piwicms\System\CoreBundle\Helper\Datatable */
        $dataTable = $this->get('piwicms.datatable');
        $dataTable->setRepository($repository);
        $dataTable->setMetadata($metadata);
        $dataTable->setRequest($request->query->all());
        $dataTable->setParameters(array(
            'id',
            'title',
            'unread',
            'created',
            'fromUser.firstname',
            'fromUser.middlename',
            'fromUser.surname'
        ));
        $dataTable->setAdvancedQuery(
            function($repository, $qb, $associations) use($userId)
            {
                /** @var $qb \Doctrine\ORM\QueryBuilder */
                $qb->andWhere($qb->expr()->eq($associations[0]['entityName'].'.toUser', ':userId'));
                $qb->setParameter('userId', $userId);
                $qb->orderBy($associations[3]['fullName'], 'DESC');
            }
        );
        $dataTable->makeSearch();
        $results = $dataTable->getSearchResults();

        // create a JSON-response with a 200 status code
        $response = new Response(json_encode($results));
        return $response;
    }

    public function indexAction()
    {
        $entity = new PrivateMessage();
        $form = $this->createForm(new PrivateMessageType(), $entity);
        return $this->render(
            'PiwicmsAdminPrivateMessageBundle:PrivateMessage:index.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    public function ajaxShowAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $entity PrivateMessage */
        $entity = $em->getRepository('PiwicmsSystemCoreBundle:PrivateMessage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PrivateMessage entity.');
        }

        // Set unread to false
        $entity->setUnread(false);
        $em->persist($entity);
        $em->flush();

        $serializer = $this->get('jms_serializer');
        $serializedEntity = $serializer->serialize($entity, 'json');
        return new Response($serializedEntity);
    }

    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new PrivateMessage();

        $form = $this->createForm(new PrivateMessageType(), $entity);

        if($request->isMethod('POST')){
            $form->submit($request);
            if($form->isValid()){
                $entity->setFromUser($this->get('security.context')->getToken()->getUser());
                if ($request->get('replyOn') > 0) {
                    $replyEntity = $em->getRepository('PiwicmsSystemCoreBundle:PrivateMessage')->find($request->get('replyOn'));
                    if ($replyEntity) {
                        $entity->setReplyOn($replyEntity);
                    }
                }
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'Private message send!'
                );

                return $this->redirect($this->generateUrl('piwicms_admin_privatemessage_index'));
            }
        }

        return $this->render('PiwicmsAdminPrivateMessageBundle:PrivateMessage:new.html.twig', array(
            'form'   => $form->createView(),
        ));
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PiwicmsSystemCoreBundle:PrivateMessage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PrivateMessage entity.');
        }

        $form = $this->createForm(new PrivateMessageType(), $entity);

        if($request->isMethod('PUT')){
            $form->submit($request);
            if($form->isValid()){
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'PrivateMessage changes are saved!'
                );

                return $this->redirect($this->generateUrl('piwicms_admin_privatemessage_index'));
            }
        }

        return $this->render('PiwicmsAdminPrivateMessageBundle:PrivateMessage:edit.html.twig', array(
            'form'   => $form->createView(),
            'entity' => $entity,
        ));
    }

    public function deleteAction(Request $request)
    {

    }

    public function ajaxDeleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PiwicmsSystemCoreBundle:PrivateMessage')->find($id);
        if ($entity) {
            $em->remove($entity);
            $em->flush();
            // create a JSON-response with a 200 status code
            $response = new Response(json_encode(array("count" => 1)));
            return $response;
        } else {
            // create a JSON-response with a 404 status code
            $response = new Response(json_encode(array("count" => 0)));
            $response->setStatusCode(404);
            return $response;
        }
    }
}