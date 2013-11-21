<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pimwiddershoven
 * Date: 10-11-13
 * Time: 13:34
 * To change this template use File | Settings | File Templates.
 */

namespace Piwicms\Admin\NewsletterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends Controller
{
    protected function generateStatisticUrl($originalUrl, $trackingId = 0, $mailingId = 0)
    {
        return $this->container->getParameter('piwicms.base_url') . $this->generateUrl('piwicms_newsletter_redirect_url', array (
            'url' => $originalUrl,
            'mailingId' => $mailingId,
            'trackingId' => $trackingId
        ));
    }

    protected function generateTrackingImage($trackingId = 0, $mailingId = 0)
    {
        $url = $this->generateUrl('piwicms_newsletter_tracking_image', array (
            'mailingId' => $mailingId,
            'trackingId' => $trackingId
        ));
        return '<img src="' . $this->container->getParameter('piwicms.base_url') . $url . '">';
    }
}