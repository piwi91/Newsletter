<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pimwiddershoven
 * Date: 10-11-13
 * Time: 13:30
 * To change this template use File | Settings | File Templates.
 */

namespace Piwicms\Admin\NewsletterBundle\Controller;

use Piwicms\System\CoreBundle\Entity\MailingStatistic;
use Symfony\Component\HttpFoundation\Response;
use Piwicms\Admin\NewsletterBundle\Controller\BaseController;

class StatisticsController extends BaseController
{
    public function redirectAction($url, $mailingId, $trackingId)
    {
        $em = $this->getDoctrine()->getManager();
        $mailingEntity = $em->getRepository('PiwicmsSystemCoreBundle:Mailing')->find($mailingId);
        $mailingUserEntity = $em->getRepository('PiwicmsSystemCoreBundle:MailingUser')->find($trackingId);

        $this->addStatistic('url', $mailingEntity, $mailingUserEntity, $url);

        return $this->redirect(urldecode($url));
    }

    public function trackingImageAction($mailingId, $trackingId)
    {
        $em = $this->getDoctrine()->getManager();
        $mailingEntity = $em->getRepository('PiwicmsSystemCoreBundle:Mailing')->find($mailingId);
        $mailingUserEntity = $em->getRepository('PiwicmsSystemCoreBundle:MailingUser')->find($trackingId);

        $this->addStatistic('viewed', $mailingEntity, $mailingUserEntity);

        $image = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=');

        return new Response($image, 200, array('Content-Type' => 'image/png'));
    }

    protected function addStatistic($type, $mailingEntity, $mailingUserEntity, $url = null)
    {
        $em = $this->getDoctrine()->getManager();

        if (!empty($mailingEntity) && !empty($mailingUserEntity)) {
            $mailingStatistic = new MailingStatistic();
            $mailingStatistic->setDatetime(new \DateTime());
            $mailingStatistic->setType($type);
            $mailingStatistic->setUrl($url);
            $mailingStatistic->setMailing($mailingEntity);
            $mailingStatistic->setMailingUser($mailingUserEntity);

            $em->persist($mailingStatistic);
            $em->flush();
        }
    }
}