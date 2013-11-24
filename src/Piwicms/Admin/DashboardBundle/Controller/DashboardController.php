<?php

namespace Piwicms\Admin\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function indexAction()
    {
        return $this->render('PiwicmsAdminDashboardBundle:Dashboard:index.html.twig');
    }
}
