<?php
namespace Piwicms\System\CoreBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Piwicms\System\CoreBundle\Entity\SystemSettings;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Piwicms\System\CoreBundle\Form\SystemSettingsType;

/**
 * Controller managing the user settings
 *
 */
class TestController extends Controller
{

    /**
     * Edit the user
     */
    public function indexAction(Request $request)
    {
//        $msg = array('user_id' => 1235, 'image_path' => '/path/to/new/pic.png');
//        $this->get('old_sound_rabbit_mq.upload_picture_producer')->publish(serialize($msg));
//        return new Response('test');
        return $this->render('PiwicmsSystemCoreBundle:Test:index.html.twig', array(
        ));
    }
}
