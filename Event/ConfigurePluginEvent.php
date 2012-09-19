<?php 
namespace Neutron\MvcBundle\Event;

use Symfony\Component\EventDispatcher\Event;

use Neutron\MvcBundle\Plugin\PluginFactoryInterface;

use Neutron\MvcBundle\Plugin\PluginInterface;

class ConfigurePluginEvent extends Event
{

    private $factory;
    
    private $plugin;

    public function __construct(PluginFactoryInterface $factory, PluginInterface $plugin)
    {
        $this->factory = $factory;
        $this->plugin = $plugin;
    }


    public function getFactory()
    {
        return $this->factory;
    }

    public function getPlugin()
    {
        return $this->plugin;
    }

}