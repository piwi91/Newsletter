<?php
namespace Piwicms\System\UserBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Class UserSettingService
 * @package Piwicms\System\UserBundle\Services
 */
class UserSettingService
{
    protected $em;
    protected $securityContext;

    /**
     * @param SecurityContextInterface $securityContext
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, SecurityContextInterface $securityContext)
    {
        $this->em = $em;
        $this->securityContext = $securityContext;
    }

    public function getValue($field)
    {
        $user = $this->securityContext->getToken()->getUser();

        $emRepository = $this->em->getRepository('PiwicmsSystemCoreBundle:UserSettings');
        $value = $emRepository->findFieldOfUser($user, $field)->getValue();

        return $value;
    }

    public function getValueOfUser($user, $field)
    {
        $emRepository = $this->em->getRepository('PiwicmsSystemCoreBundle:UserSettings');
        $value = $emRepository->findFieldOfUser($user, $field)->getValue();

        return $value;
    }
}