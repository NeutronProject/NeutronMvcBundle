<?php
namespace Neutron\MvcBundle\Widget;

class WidgetFactory implements WidgetFactoryInterface
{
    public function createWidget($name)
    {
        return new Widget($name);
    }
}