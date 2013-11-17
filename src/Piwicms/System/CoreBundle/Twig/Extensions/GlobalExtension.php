<?php

namespace Piwicms\System\CoreBundle\Twig\Extensions;

use \Twig_Extension;

class GlobalExtension extends \Twig_Extension
{
    public function __construct($entityManager, $securityContext)
    {
        /** @var $entityManager \Doctrine\ORM\EntityManager */
        $this->em = $entityManager;
        /** @var $securityContext \Symfony\Component\Security\Core\SecurityContext */
        $this->securityContext = $securityContext;
    }

    public function getGlobals()
    {
        return array(
            "tasks" => $this->getTasks(),
            "privateMessages" => $this->getPrivateMessages()
        );
    }

    private function getTasks()
    {
        $repository = $this->em->getRepository('PiwicmsSystemCoreBundle:Task');
        $token = $this->securityContext->getToken();
        if ($token) {
            $tasks = $repository->findTasksOfUser($token->getUser());
        } else {
            $tasks = array();
        }
        return $tasks;
    }

    private function getPrivateMessages()
    {
        $repository = $this->em->getRepository('PiwicmsSystemCoreBundle:PrivateMessage');
        $token = $this->securityContext->getToken();
        if ($token) {
            $messages = $repository->findPrivateMessagesOfUser($token->getUser());
        } else {
            $messages = array();
        }
        return $messages;
    }

    public function getName()
    {
        return 'Global_extension';
    }
}