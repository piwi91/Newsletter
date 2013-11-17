<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Piwicms\System\UserBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Piwicms\System\CoreBundle\Entity\User;
use Piwicms\System\CoreBundle\Entity\UserSettings;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Piwicms\System\UserBundle\Form\UserSettingsType;

/**
 * Controller managing the user settings
 *
 */
class UserSettingsController extends Controller
{

    /**
     * Edit the user
     */
    public function editAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $userid = $user->getId();

        //get repository of usersettings
        $emUsersettings = $this->getDoctrine()->getManager();
        $emRepository = $emUsersettings->getRepository('PiwicmsSystemCoreBundle:UserSettings');

        //get usersettings as array
        $usersettingsarr = $emRepository->findUserSettingsOfUserArray($user);

        $editForm = $this->createForm(new UserSettingsType($emUsersettings), $usersettingsarr, array(
            'show_legend' => false,
        ));

        if ($request->getMethod() == 'POST') {
            $editForm->submit($request);

            // data is an array with field values as keys
            $data = $editForm->getData();

            //loop through keys of data (= fieldnames) and save each
            foreach(array_keys($data) as $key){

                $value = $data[$key];
                if(is_object($value)){
                    //if  value is an object then get the id via default method getId(), exceptions add here.
                    if (method_exists($value, 'getId')){
                        $value = $value->getId();
                    }else{
                        throw new \Exception( "usersetting ".$key." cannot be added. ".$key." is an object and a getId() method is expected." );
                    }
                }

                //if field already exist then update else create new entity and persist to db
                $usersetting = $emRepository->findUserSettingsFieldOfUser($user, $key);
                if (!$usersetting){
                    $usersetting = new UserSettings();
                }
                $type ='';
                $usersetting->setUser($user);
                $usersetting->setSetting($key);
                $usersetting->setFieldType($type);
                $usersetting->setValue($value);
                $emUsersettings->persist($usersetting);
                $emUsersettings->flush();
            }

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('system.core.usersettings.saved')
            );
        }

        return $this->render('PiwicmsSystemUserBundle:UserSettings:edit.html.twig', array(
            'entityname' => 'UserSettings',
            'edit_form'   => $editForm->createView(),
        ));
    }
}
