<?php 
/*
 * This file is part of NeutronMvcBundle
 *
 * (c) Zender <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\MvcBundle\Provider;

use Neutron\MvcBundle\Plugin\PluginInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Default implementation of the PluginProviderInterface
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class PluginProvider implements PluginProviderInterface 
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var PluginInterface[]
     */
    private $plugins;

    /**
     * Construct
     *
     * @param ContainerInterface $container            
     * @param array $pluginsIds            
     */
    public function __construct (ContainerInterface $container, array $pluginIds)
    {
        $this->container = $container;
        $this->set($pluginIds);
    }

    
    public function all()
    {
        return $this->plugins;
    }

    public function get ($name)
    {
        if (!$this->has($name)) {
            throw new \InvalidArgumentException(sprintf('The plugin "%s" is not defined.', $name));
        }
        
        return $this->plugins[$name];
    }

    public function has ($name)
    {
        return array_key_exists($name, $this->plugins);
    }
    
    public function add(PluginInterface $plugin)
    {
        if ($this->has($plugin->getName())){
            throw new \InvalidArgumentException(
                sprintf('Plugin with name "%s" already exists.', $plugin->getName())
            );
        }
        
        $this->plugins[$plugin->getName()] = $plugin;
        return $this;
    }
    
    public function set(array $pluginIds)
    {
        $this->plugins = array();
        
        foreach ($pluginIds as $id){
            $plugin = $this->container->get($id);
            $this->add($plugin);
        }
        
        return $this;
    }
    
    public function getAsOptions()
    {
        $choices = array();
        
        foreach ($this->all() as $plugin){
            
            $choices[$plugin->getName()] = $plugin->getLabel();
        }
        
        asort($choices);
        
        return $choices;
    }
    
    public function getTreeOptions()
    {
        $options = array();
        
        foreach ($this->all() as $plugin){
            $options[] = $plugin->getTreeOptions();
        }
        
        return $options;
    }

}