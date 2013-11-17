<?php

namespace Piwicms\Admin\ViewBundle\Twig;

use Doctrine\DBAL\DBALException;
use Twig_LoaderInterface;
use Twig_Error_Loader;

class DatabaseLoader implements Twig_LoaderInterface
{
    private $entityManager;
    private $logger;

    public function __construct($entityManager, $logger)
    {
        /** @var $entityManager \Doctrine\ORM\EntityManager */
        $this->em = $entityManager;
        /** @var $logger \Symfony\Bridge\Monolog\Logger */
        $this->logger = $logger;
    }

    public function getSource($name)
    {
        try {
            $this->logger->debug("DatabaseLoader::getSource() called with parameters[name: " . $name . "]");

            $view = $this->em->getRepository('PiwicmsSystemCoreBundle:View')->findOneByName($name);

            if ($view instanceof \Piwicms\System\CoreBundle\Entity\View) {
                $this->logger->debug("DatabaseLoader::getSource() View was found. Returning its content.");
                return $view->getView();
            } else {
                throw new Twig_Error_Loader(sprintf('TwigDatabase: Unable to find view "%s".', $name));
            }
        } catch (DBALException $e) {
            throw new Twig_Error_Loader(sprintf('TwigDatabase: Unable to find view "%s".', $name));
        }

    }

    public function isFresh($name, $time)
    {
        $this->logger->debug(
            "DatabaseLoader::isFresh() called with parameters[name: " . $name . ", time:" . $time . "]"
        );

        $view = $this->em->getRepository(
            'PiwicmsSystemCoreBundle:View'
        )->findOneByName($name);

        if ($view instanceof \Piwicms\System\CoreBundle\Entity\View) {
            $viewTimestamp = $view->getModified()->getTimestamp();
            $this->logger->debug("DatabaseLoader::isFresh() View was found. Returning its fresh status");
            return ($viewTimestamp <= $time);
        } else {
            throw new Twig_Error_Loader(sprintf('TwigDatabase: Unable to find view "%s".', $name));
        }
    }

    public function getCacheKey($name)
    {
        $this->logger->debug("DatabaseLoader::getCacheKey() called with parameters[name: " . $name . "]");

        $view = $this->em->getRepository(
            'PiwicmsSystemCoreBundle:View'
        )->findOneByName($name);
        if ($view instanceof \Piwicms\System\CoreBundle\Entity\View) {
            $viewTimestamp = $view->getModified()->getTimestamp();
            return "twig:db:" . $name . $viewTimestamp;
        } else {
            return "twig:db:" . $name . 0;
        }
    }

}