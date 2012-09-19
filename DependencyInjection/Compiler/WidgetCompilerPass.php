<?php 
/*
 * This file is part of NeutronMvcBundle
 *
 * (c) Zender <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\MvcBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Default implementation of CompilerPassInterface
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class WidgetCompilerPass implements CompilerPassInterface
{
    public function process (ContainerBuilder $container)
    {
        if (!$container->hasDefinition('neutron_mvc.widget_provider')) {
            return;
        }
                
        $definition = $container->getDefinition('neutron_mvc.widget_provider');
        
        $widgets = array();
        
        foreach ($container->findTaggedServiceIds('neutron.widget') as $id => $tags) {
            foreach ($tags as $attributes) {
                if (empty($attributes['alias'])) {
                    throw new \InvalidArgumentException(
                        sprintf('The alias is not defined in the "neutron.widget" tag for the service "%s"', $id)
                    );
                }
                
                $widgets[$attributes['alias']] = $id;
            }
        }
        
        $definition->replaceArgument(1, $widgets);
    }
}
