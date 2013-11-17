<?php

namespace Piwicms\Admin\FileExplorerBundle\Controller;

use Piwicms\System\CoreBundle\Entity\PrivateMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Piwicms\Admin\PrivateMessageBundle\Form\PrivateMessageType;
use Symfony\Component\HttpFoundation\Response;

class FileExplorerController extends Controller
{
    public function indexAction()
    {
        return $this->render(
            'PiwicmsAdminFileExplorerBundle:FileExplorer:index.html.twig'
        );
    }
}