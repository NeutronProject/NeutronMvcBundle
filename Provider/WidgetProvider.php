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

use Neutron\MvcBundle\Widget\WidgetInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Default implementation of the WidgetProviderInterface
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class WidgetProvider implements WidgetProviderInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var WidgetInterface[]
     */
    private $widgets;

    /**
     * Construct
     *
     * @param ContainerInterface $container            
     * @param array $widgetIds            
     */
    public function __construct (ContainerInterface $container, array $widgetIds)
    {
        $this->container = $container;
        $this->set($widgetIds); 
    }

    public function all()
    {
        return $this->widgets;
    }
    
    public function getAvailableWidgets(PluginInterface $plugin)
    {
        $availableWidgets = array();
        foreach ($this->widgets as $widget){
            if ($widget->canUsePlugin($plugin)){
                $availableWidgets[] = $widget;
            }          
        }
        
        return $availableWidgets;
    }

    public function get ($name)
    {
        if (!$this->has($name)) {
            throw new \InvalidArgumentException(sprintf('The plugin "%s" is not defined.', $name));
        }
        
        return $this->widgets[$name];
    }

    public function has ($name)
    {
        return array_key_exists($name, $this->widgets);
    }
    
    public function add(WidgetInterface $widget)
    {  
        if ($this->has($widget->getName())){
            throw new \InvalidArgumentException(
                sprintf('Widget with name "%s" already exist.', $widget->getName())
            );
        }
     
        $this->widgets[$widget->getName()] = $widget;
        return $this;
    }
    
    public function set(array $widgetIds)
    {
        $this->widgets = array();
    
        foreach ($widgetIds as $id){
            $widget = $this->container->get($id); 
            $this->add($widget);
        }
    
        return $this;
    }

}