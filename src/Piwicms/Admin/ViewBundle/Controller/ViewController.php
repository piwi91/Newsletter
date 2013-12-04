<?php

namespace Piwicms\Admin\ViewBundle\Controller;

use Piwicms\System\CoreBundle\Entity\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Piwicms\Admin\ViewBundle\Form\ViewType;
use Symfony\Component\HttpFoundation\Response;

class ViewController extends Controller
{
    public function indexDatatableAction(Request $request)
    {
        /** @var $dataTable \Piwicms\System\CoreBundle\Helper\Datatable */
        $dataTable = $this->get('piwicms.datatable');
        $dataTable->setEntity('PiwicmsSystemCoreBundle:View', 'view');
        $dataTable->setRequest($request);
        $dataTable->setSelectParameters(array(
            'id',
            'name',
            'module',
            'createdBy',
            'created',
            'modified'
        ));
        $dataTable->makeSearch();
        return $dataTable->sendResponse();
    }

    public function indexAction()
    {
        return $this->render('PiwicmsAdminViewBundle:View:index.html.twig');
    }

    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new View();

        $form = $this->createForm(new ViewType(), $entity);

        if($request->isMethod('POST')){
            $form->submit($request);
            if($form->isValid()){
                $entity->setCreatedBy($this->get('security.context')->getToken()->getUsername());
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'View is saved!'
                );

                return $this->redirect($this->generateUrl('piwicms_admin_view_index'));
            }
        }

        return $this->render('PiwicmsAdminViewBundle:View:new.html.twig', array(
            'form'   => $form->createView(),
        ));
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PiwicmsSystemCoreBundle:View')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find View entity.');
        }

        $form = $this->createForm(new ViewType(), $entity);

        if($request->isMethod('PUT')){
            $form->submit($request);
            if($form->isValid()){
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'View changes are saved!'
                );

                return $this->redirect($this->generateUrl('piwicms_admin_view_index'));
            }
        }

        return $this->render('PiwicmsAdminViewBundle:View:edit.html.twig', array(
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
        $entity = $em->getRepository('PiwicmsCoreBundle:View')->find($id);
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

    public function ajaxRenderedViewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $entity View */
        $entity = $em->getRepository('PiwicmsSystemCoreBundle:View')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find View entity.');
        }

        // TODO: Render and return response in json
        $renderedView = $this->renderView($entity->getName());

        $serializer = $this->get('jms_serializer');
        $serializedView = $serializer->serialize(array('view' => $renderedView, 'entity' => $entity), 'json');
        return new Response($serializedView);
    }
}