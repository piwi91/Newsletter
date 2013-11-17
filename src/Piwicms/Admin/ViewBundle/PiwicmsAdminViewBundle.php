<?php

namespace Piwicms\Admin\ViewBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Piwicms\Admin\ViewBundle\DependencyInjection\Compiler\TwigDatabaseLoaderPass;

class PiwicmsAdminViewBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new TwigDatabaseLoaderPass());
    }
}
