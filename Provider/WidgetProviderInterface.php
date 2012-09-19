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

/**
 * Interface implemented by a WidgetProvider class.
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
interface WidgetProviderInterface
{
    
    /**
     * Retrieves a widget by its name
     *
     * @param string $name            
     * @return \Neutron\MvcBundle\Widget\WidgetInterface
     * @throws \InvalidArgumentException if the tree does not exists
     */
    public function get ($name);

    /**
     * Checks whether a plugin exists in this provider
     *
     * @param string $name            
     * @return bool
     */
    public function has ($name);

    public function all();
    
    public function add(WidgetInterface $widget);
    
    public function set(array $widgetIds);
    
    public function getAvailableWidgets(PluginInterface $plugin);
    
    
}
