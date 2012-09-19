<?php
namespace Neutron\MvcBundle\Model\Widget;

interface WidgetManagerInterface
{
    public function getInstances($locale);
    
    public function get($identifier);
}