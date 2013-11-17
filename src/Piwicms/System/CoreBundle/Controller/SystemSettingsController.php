<?php
namespace Piwicms\System\CoreBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Piwicms\System\CoreBundle\Entity\SystemSettings;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Piwicms\System\CoreBundle\Form\SystemSettingsType;

/**
 * Controller managing the user settings
 *
 */
class SystemSettingsController extends Controller
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

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('PiwicmsSystemCoreBundle:SystemSettings');

        //get usersettings as array
        $settingsArray = $repository->findSystemSettingsArray();

        $editForm = $this->createForm(new SystemSettingsType(), $settingsArray, array(
            'show_legend' => false,
        ));

        if ($request->getMethod() == 'POST') {
            $editForm->submit($request);

            // data is an array with field values as keys
            $data = $editForm->getData();

            //loop through keys of data (= fieldnames) and save each
            foreach(array_keys($data) as $key){

                // Module
                $module = $data[$key];

                foreach(array_keys($data[$key]) as $settingKey){

                    $value = $data[$key][$settingKey];

                    if(is_object($value)){
                        //if  value is an object then get the id via default method getId(), exceptions add here.
                        if (method_exists($value, 'getId')){
                            $value = $value->getId();
                        }else{
                            throw new \Exception( "Setting ".$key." cannot be added. ".$key." is an object and a getId() method is expected." );
                        }
                    }

                    //if field already exist then update else create new entity and persist to db
                    $setting = $repository->findSystemSettingsField($key, $settingKey);
                    if (!$setting){
                        $setting = new SystemSettings();
                    }
                    $type ='';
                    $setting->setModifiedBy($user);
                    $setting->setModule($key);
                    $setting->setSetting($settingKey);
                    $setting->setFieldType($type);
                    $setting->setValue($value);
                    $em->persist($setting);
                    $em->flush();
                }
            }

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('system.core.settings.saved')
            );
        }

        return $this->render('PiwicmsSystemCoreBundle:SystemSettings:edit.html.twig', array(
            'entityname' => 'SystemSettings',
            'edit_form'   => $editForm->createView(),
        ));
    }
}
