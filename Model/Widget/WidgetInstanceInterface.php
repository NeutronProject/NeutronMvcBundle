<?php
namespace Neutron\MvcBundle\Model\Widget;

interface WidgetInstanceInterface
{
    public function getIdentifier();
    
    public function getLabel();
}