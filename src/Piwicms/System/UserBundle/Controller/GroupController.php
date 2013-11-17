<?php

namespace Piwicms\System\UserBundle\Controller;

use FOS\GroupBundle\Form\Type\GroupFormType;
use Symfony\Component\Config\Definition\Exception\DuplicateKeyException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Symfony\Component\HttpFoundation\Response;
use Piwicms\System\CoreBundle\Entity\Group;
use Symfony\Component\Security\Core\Util\SecureRandom;

/**
 * Controller managing the user settings
 *
 */
class GroupController extends Controller
{
    public function indexDatatableAction(Request $request)
    {
        /** @var $dataTable \Piwicms\System\CoreBundle\Helper\Datatable */
        $dataTable = $this->get('piwicms.datatable');
        $dataTable->setEntity('PiwicmsSystemCoreBundle:Group', '_group');
        $dataTable->setRequest($request);
        $dataTable->setSelectParameters(array(
            'id',
            'name',
            'roles'
        ));
        $dataTable->makeSearch();
        return $dataTable->sendResponse();
    }

    public function indexAction()
    {
        return $this->render(
            'PiwicmsSystemUserBundle:Group:index.html.twig'
        );
    }

    public function newAction(Request $request)
    {

        /** @var $groupManager \FOS\UserBundle\Model\GroupManagerInterface */
        $groupManager = $this->get('fos_user.group_manager');
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.group.form.factory');

        $group = $groupManager->createGroup('');

        $form = $formFactory->createForm();
        $form->setData($group);

        if($request->isMethod('POST')){

            $form->submit($request);

            if($form->isValid()){
                /** @var $userManager FOS\GroupBundle\Doctrine\GroupManager */
                $groupManager = $this->get('fos_user.group_manager');
                $groupManager->updateGroup($group);

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'Group is saved!'
                );

                return $this->redirect($this->generateUrl('piwicms_admin_group_index'));
            }
        }

        return $this->render('PiwicmsSystemUserBundle:Group:new.html.twig', array(
            'form'   => $form->createView(),
        ));
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $groupEntity = $em->getRepository('PiwicmsSystemCoreBundle:Group')->find($id);

        if (!$groupEntity) {
            throw $this->createNotFoundException('Unable to find OrSchedule entity.');
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.group.form.factory');

        $form = $formFactory->createForm();
        $form->setData($groupEntity);

        $deleteForm = $this->createDeleteForm($id);

        if($request->isMethod('PUT')){
            $form->submit($request);
            if($form->isValid()){
                /** @var $groupManager \FOS\UserBundle\Model\GroupManagerInterface */
                $groupManager = $this->get('fos_user.group_manager');
                $groupManager->updateGroup($groupEntity);

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'Group changes are saved!'
                );

                return $this->redirect($this->generateUrl('piwicms_admin_group_index'));
            }
        }

        return $this->render('PiwicmsSystemUserBundle:Group:edit.html.twig', array(
            'form'   => $form->createView(),
            'delete_form' => $deleteForm->createView(),
            'entity' => $groupEntity,
        ));
    }

    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PiwicmsSystemCoreBundle:Group')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Group entity.');
            }

            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                'Group is deleted!'
            );
        }

        return $this->redirect($this->generateUrl('piwicms_admin_group_index'));
    }

    /**
     * Creates a form to delete a OrSchedule entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
            ;
    }
}
