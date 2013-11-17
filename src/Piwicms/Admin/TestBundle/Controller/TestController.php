<?php

namespace Piwicms\Admin\TestBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    public function indexAction()
    {
//        return $this->render(
//            'PiwicmsAdminTestBundle:Test:index.html.twig',
//            array(
//                'test' => 'test123'
//            )
//        );
        return $this->render(
        'WiddershovenNewsletter',
        array(
            'test' => 'test123'
        )
    );
    }
}