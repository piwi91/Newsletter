<?php

namespace Piwicms\Admin\TaskBundle\Controller;

use Piwicms\System\CoreBundle\Entity\Task;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Piwicms\Admin\TaskBundle\Form\TaskType;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function indexDatatableAction(Request $request)
    {
        /** @var $dataTable \Piwicms\System\CoreBundle\Helper\Datatable */
        $dataTable = $this->get('piwicms.datatable');
        $dataTable->setEntity('PiwicmsSystemCoreBundle:Task', 'task');
        $dataTable->setRequest($request);
        $dataTable->setSelectParameters(array(
            'id',
            'title',
            'unread',
            'date',
            'createdBy'
        ));
        $dataTable->setAdvancedWhere(
            function($qb)
            {
                /** @var $qb \Doctrine\ORM\QueryBuilder */
                $qb->orderBy('task.date', 'DESC');
            }
        );
        $dataTable->makeSearch();
        return $dataTable->sendResponse();
    }

    public function indexAction()
    {
        $entity = new Task();
        $form = $this->createForm(new TaskType(), $entity);
        return $this->render(
            'PiwicmsAdminTaskBundle:Task:index.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    public function ajaxShowAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $entity Task */
        $entity = $em->getRepository('PiwicmsSystemCoreBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
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
        $entity = new Task();

        $form = $this->createForm(new TaskType(), $entity);

        if($request->isMethod('POST')){
            $form->submit($request);
            if($form->isValid()){
                $entity->setCreatedBy($this->get('security.context')->getToken()->getUsername());
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'Task added!'
                );

                return $this->redirect($this->generateUrl('piwicms_admin_task_index'));
            }
        }

        return $this->render('PiwicmsAdminTaskBundle:Task:new.html.twig', array(
            'form'   => $form->createView(),
        ));
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PiwicmsSystemCoreBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $form = $this->createForm(new TaskType(), $entity);

        if($request->isMethod('PUT')){
            $form->submit($request);
            if($form->isValid()){
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'Task changes are saved!'
                );

                return $this->redirect($this->generateUrl('piwicms_admin_task_index'));
            }
        }

        return $this->render('PiwicmsAdminTaskBundle:Task:edit.html.twig', array(
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
        $entity = $em->getRepository('PiwicmsSystemCoreBundle:Task')->find($id);
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