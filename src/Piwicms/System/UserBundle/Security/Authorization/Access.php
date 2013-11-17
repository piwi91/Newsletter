<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Martijn van Eldijk
 * Date: 4/22/13
 * Time: 9:21 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Piwicms\System\UserBundle\Security\Authorization;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use ErrorException;

class Access
{
    private $securityContext;

    protected $em;

    public function __construct(SecurityContextInterface  $securityContext, EntityManager $em )
    {
        $this->securityContext = $securityContext;
        $this->em = $em;

    }

    /**
     * @param $domainobject string name of domainobject
     * @param $permission string name of permission
     * @param $exception bool whether or not an exception must be shown when user has no access
     * @return bool
     * @throws \ErrorException
     */
    public function isGranted($domainobject, $permission, $exception = true)
    {
        $er = $this->em->getRepository('PiwicmsSystemCoreBundle:DomainObject');
        $do = $er->findOneByName($domainobject);
        if (!is_object($do)){
            throw new ErrorException('DomainObject "'.$domainobject.'" does not exist.');
        }
        $oid = ObjectIdentity::fromDomainObject($do);
        if (!$this->securityContext->isGranted(strtoupper($permission), $oid)){
            if ($exception){
                throw new AccessDeniedException('Not granted');
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

}