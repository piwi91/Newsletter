<?php

namespace Piwicms\System\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Piwicms\System\CoreBundle\Entity\Group;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManager;

/**
 * Listener responsible for adding the default user role at registration
 */
class RegistrationListener implements EventSubscriberInterface
{
    protected $em;
    protected $user;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
        );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        $defaultGroup = 'Guests';
        $groupEntity = $this->em->getRepository('PiwicmsSystemCoreBundle:Group')->findOneByName($defaultGroup);
        if (!$groupEntity) {
            $groupEntity = new Group('Guests', array('ROLE_GUEST'));
            $this->em->persist($groupEntity);
            $this->em->flush();
        }
        /** @var $user \Piwicms\System\CoreBundle\Entity\User */
        $user = $event->getForm()->getData();
        $user->addGroup($groupEntity);
    }
}