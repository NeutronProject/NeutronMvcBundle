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

/**
 * Interface implemented by a PluginProvider class.
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
interface PluginProviderInterface
{
    
    /**
     * Retrieves a plugin by its name
     *
     * @param string $name            
     * @return \Neutron\MvcBundle\Plugin\PluginInterface
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
    
    public function add(PluginInterface $plugin);
    
    public function set(array $pluginIds);
    
    public function getAsOptions();
    
    public function getTreeOptions();
    
    
}
