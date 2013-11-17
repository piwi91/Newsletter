<?php

namespace Piwicms\Admin\ViewBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PiwicmsAdminViewExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        /**
         * Set some references to let other bundles hook to the proper
         * loader.
         */
//        $container->setAlias('twig.loader', 'piwicms.twig.twig_chain_loader');
//        $container->setAlias('twig.loader.filesystem', 'piwicms.twig.filesystem');

        /**
         * Add the loaders defined in the configuration mapping.
         * Since twig chain loader doesn't feature priority sorting,
         * sort them before appending.
//         */
//        $twigChainLoader = $container->getDefinition('piwicms.twig.twig_chain_loader');
//        $twigChainLoader->addMethodCall('setLoader', array(new Reference('piwicms.twig.databaseloader')));
//        $twigChainLoader->addMethodCall('addLoader', array(new Reference('piwicms.twig.filesystem')));
//
//        // @TODO bugfix: twig loader is not using default 'addMethodCall'
//        // from symfony twig extension.
//        $reflClass = new \ReflectionClass('Symfony\Bridge\Twig\Extension\FormExtension');
//        $stdSfPath = dirname(dirname($reflClass->getFileName())).'/Resources/views/Form';
//        $container
//            ->getDefinition('piwicms.twig.twig_chain_loader')
//            ->addMethodCall('addPath', array($stdSfPath));
    }
}
