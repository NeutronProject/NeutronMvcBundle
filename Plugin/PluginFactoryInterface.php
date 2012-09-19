<?php
namespace Neutron\MvcBundle\Plugin;

interface PluginFactoryInterface
{
    public function createPlugin($name);
    
    public function createPanel($name, array $options = array());
}