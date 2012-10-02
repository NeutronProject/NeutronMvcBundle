<?php 
namespace Neutron\MvcBundle\Plugin;

use Neutron\MvcBundle\Panel\Panel;

class PluginFactory implements PluginFactoryInterface 
{
    public function createPlugin($name)
    {
        return new Plugin($name);
    }
    
    public function createPanel($name, array $options = array())
    {
        return new Panel($name, $options);
    }
}