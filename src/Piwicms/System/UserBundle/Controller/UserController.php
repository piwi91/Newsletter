<?php

namespace Piwicms\System\UserBundle\Controller;

use Piwicms\System\UserBundle\Form\UserFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Symfony\Component\HttpFoundation\Response;
use Piwicms\System\CoreBundle\Entity\User;

/**
 * Controller managing the user settings
 *
 */
class UserController extends Controller
{
    public function indexDatatableAction(Request $request)
    {
        /** @var $dataTable \Piwicms\System\CoreBundle\Helper\Datatable */
        $dataTable = $this->get('piwicms.datatable');
        $dataTable->setEntity('PiwicmsSystemCoreBundle:User', 'user');
        $dataTable->setRequest($request);
        $dataTable->setSelectParameters(array(
            'id',
            'username',
            'email',
            'firstname',
            'middlename',
            'surname'
        ));
        $dataTable->makeSearch();
        return $dataTable->sendResponse();
    }

    public function indexAction()
    {
        return $this->render(
            'PiwicmsSystemUserBundle:User:index.html.twig'
        );
    }

    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userEntity = new User();

        $form = $this->createForm(new UserFormType(), $userEntity);

        if($request->isMethod('POST')){

            $form->submit($request);
            $formData = $form->getData();
            $email = $formData->getEmail();

            $userRepository = $em->getRepository('PiwicmsSystemCoreBundle:User');
            if ($userRepository->findUserByEmail($email)) {
                $this->get('session')->getFlashBag()->add(
                    'danger',
                    'User with emailaddress '.$email.' already exists'
                );
                return $this->render('PiwicmsSystemUserBundle:User:new.html.twig', array(
                    'form'   => $form->createView(),
                ));
            }

            if($form->isValid()){
                $tokenGenerator = $this->get('fos_user.util.token_generator');
                $password = substr($tokenGenerator->generateToken(), 0, 12);

                $userManager = $this->get('fos_user.user_manager');
                $userEntity->setPlainPassword($password);
                $userManager->updateUser($userEntity);

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'User is saved! (password = '.$password.')'
                );

                return $this->redirect($this->generateUrl('piwicms_admin_user_index'));
            }
        }

        return $this->render('PiwicmsSystemUserBundle:User:new.html.twig', array(
            'form'   => $form->createView(),
        ));
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userEntity = $em->getRepository('PiwicmsSystemCoreBundle:User')->find($id);

        if (!$userEntity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $form = $this->createForm(new UserFormType(), $userEntity);
        $deleteForm = $this->createDeleteForm($id);

        if($request->isMethod('PUT')){
            $form->submit($request);
            if($form->isValid()){
                $userManager = $this->get('fos_user.user_manager');
                $userManager->updateUser($userEntity);

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'User changes are saved!'
                );

                return $this->redirect($this->generateUrl('piwicms_admin_user_index'));
            }
        }

        return $this->render('PiwicmsSystemUserBundle:User:edit.html.twig', array(
            'form'   => $form->createView(),
            'delete_form' => $deleteForm->createView(),
            'entity' => $userEntity,
        ));
    }

    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PiwicmsSystemCoreBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                'User is deleted!'
            );
        }

        return $this->redirect($this->generateUrl('piwicms_admin_user_index'));
    }

    /**
     * Creates a form to delete a User entity by id.
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
