<?php
namespace Neutron\MvcBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('neutron_mvc');

        $this->addGeneralConfigurations($rootNode);
        $this->addCategoryConfigurations($rootNode);

        return $treeBuilder;
    }
    
    private function addGeneralConfigurations(ArrayNodeDefinition $node)
    {
        $node
            ->children()                
                ->scalarNode('dashboard_controller')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('home_controller')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('translation_domain')->defaultValue('NeutronMvcBundle')->end()
            ->end()
        ;
    }
    
        
    private function addCategoryConfigurations(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('category')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('controller')->defaultValue('neutron_mvc.controller.backend.category.default')->end()
                        ->scalarNode('manager')->defaultValue('neutron_mvc.category.manager.default')->end()
                        ->scalarNode('category_class')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('tree')->defaultValue('category')->end()
                        ->scalarNode('grid')->defaultValue('category')->end()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue('neutron_mvc_category')->end()
                                ->scalarNode('handler')->defaultValue('neutron_mvc.form.handler.category.default')->end()
                                ->scalarNode('name')->defaultValue('neutron_mvc_category')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
