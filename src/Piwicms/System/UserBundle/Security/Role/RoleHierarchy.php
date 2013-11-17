<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Martijn van Eldijk
 * Date: 4/11/13
 * Time: 1:40 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Piwicms\System\UserBundle\Security\Role;

use Symfony\Component\Security\Core\Role\RoleHierarchy as BaseRoleHierarchy;
use Doctrine\ORM\EntityManager;


class RoleHierarchy extends BaseRoleHierarchy
{
    private $em;

    /**
     * @param array $hierarchy
     */
    public function __construct(array $hierarchy, EntityManager $em)
    {
        $this->em = $em;
        parent::__construct($this->buildRolesTree());
    }

    /**
     * Here we build an array with roles. It looks like a two-levelled tree - just
     * like original Symfony roles are stored in security.yml
     * @return array
     */
    private function buildRolesTree()
    {
        $hierarchy = array();
        $roles = $this->em->createQuery('select r from PiwicmsSystemCoreBundle:Role r')->execute();
        foreach ($roles as $role) {
            /** @var $role Role */
            if ($role->getParent()) {
                if (!isset($hierarchy[$role->getParent()->getRole()])) {
                    $hierarchy[$role->getParent()->getRole()] = array();
                }
                $hierarchy[$role->getParent()->getRole()][] = $role->getRole();
            } else {
                if (!isset($hierarchy[$role->getRole()])) {
                    $hierarchy[$role->getRole()] = array();
                }
            }
        }
        return $hierarchy;
    }
}