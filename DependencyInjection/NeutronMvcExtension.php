<?php

namespace Neutron\MvcBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NeutronMvcExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        
        $this->loadServices($config, $container, $loader);
        $this->loadCategory($config['category'], $container, $loader);
    }
    
    
    private function loadServices(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('services.xml');
        
        $container->setParameter('neutron_mvc.dashboard_controller', $config['dashboard_controller']);
        $container->setParameter('neutron_mvc.home_controller', $config['home_controller']);
        $container->setParameter('neutron_mvc.translation_domain', $config['translation_domain']);
        $container->setAlias('neutron_mvc.mvc_manager', 'neutron_mvc.doctrine.mvc_manager');
    }
    
    private function loadCategory(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('category.xml');
    
        $container->setParameter('neutron_mvc.controller.backend.category', $config['controller']);
        $container->setParameter('neutron_mvc.category.category_class', $config['category_class']);
        $container->setParameter('neutron_mvc.category.tree', $config['tree']);
        $container->setParameter('neutron_mvc.category.grid', $config['grid']);
        
        $container->setAlias('neutron_mvc.category.manager', $config['manager']);
        $container->setAlias('neutron_mvc.form.handler.category', $config['form']['handler']);
        $container->setParameter('neutron_mvc.category.form.type', $config['form']['type']);
        $container->setParameter('neutron_mvc.category.form.name', $config['form']['name']);
    }
}
