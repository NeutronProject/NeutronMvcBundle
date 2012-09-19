<?php
namespace Neutron\MvcBundle;

use Neutron\MvcBundle\DependencyInjection\Compiler\WidgetCompilerPass;

use Neutron\MvcBundle\DependencyInjection\Compiler\PluginCompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NeutronMvcBundle extends Bundle
{
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\HttpKernel\Bundle.Bundle::build()
     */
    public function build (ContainerBuilder $container)
    {
        parent::build($container);
    
        $container->addCompilerPass(new PluginCompilerPass());
        $container->addCompilerPass(new WidgetCompilerPass());
    }
}
