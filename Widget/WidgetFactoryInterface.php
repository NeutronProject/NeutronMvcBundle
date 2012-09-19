<?php
namespace Neutron\MvcBundle\Widget;

interface WidgetFactoryInterface
{
    public function createWidget($name);
}