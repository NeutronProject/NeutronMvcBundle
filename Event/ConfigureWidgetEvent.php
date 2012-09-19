<?php 

namespace Neutron\MvcBundle\Event;

use Symfony\Component\EventDispatcher\Event;

use Neutron\MvcBundle\Widget\WidgetFactoryInterface;

use Neutron\MvcBundle\Widget\WidgetInterface;

class ConfigureWidgetEvent extends Event
{

    private $factory;
    
    private $widget;

    public function __construct(WidgetFactoryInterface $factory, WidgetInterface $widget)
    {
        $this->factory = $factory;
        $this->widget = $widget;
    }


    public function getFactory()
    {
        return $this->factory;
    }

    public function getWidget()
    {
        return $this->widget;
    }

}