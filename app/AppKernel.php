<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new \Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Craue\FormFlowBundle\CraueFormFlowBundle(),
            new \FOS\UserBundle\FOSUserBundle(),
            new \FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new OldSound\RabbitMqBundle\OldSoundRabbitMqBundle(),
            new YZ\SupervisorBundle\YZSupervisorBundle(),
            new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new FM\ElfinderBundle\FMElfinderBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),


            /* PiwiCMS Bundles */
            new Piwicms\System\CoreBundle\PiwicmsSystemCoreBundle(),
            new Piwicms\System\UserBundle\PiwicmsSystemUserBundle(),
            new Piwicms\Admin\ViewBundle\PiwicmsAdminViewBundle(),
            new Piwicms\Admin\PrivateMessageBundle\PiwicmsAdminPrivateMessageBundle(),
            new Piwicms\Admin\TaskBundle\PiwicmsAdminTaskBundle(),
            new Piwicms\Admin\TestBundle\PiwicmsAdminTestBundle(),
            new Piwicms\Admin\FileExplorerBundle\PiwicmsAdminFileExplorerBundle(),
            new Piwicms\Admin\NewsletterBundle\PiwicmsAdminNewsletterBundle(),
            new Piwicms\Client\NewsletterBundle\PiwicmsClientNewsletterBundle(),
            new Piwicms\Client\CoreBundle\PiwicmsClientCoreBundle(),
            new Piwicms\Admin\WebBundle\PiwicmsAdminWebBundle(),
            new Piwicms\Admin\DashboardBundle\PiwicmsAdminDashboardBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Acme\DemoBundle\AcmeDemoBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
