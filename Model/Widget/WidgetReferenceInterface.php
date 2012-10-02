<?php 
namespace Neutron\MvcBundle\Model\Widget;

use Neutron\MvcBundle\Widget\WidgetInterface;

interface WidgetReferenceInterface
{
    
    public function setPluginInstanceId($pluginInstanceId);
    
    public function getPluginInstanceId();

    public function setPluginIdentifier($pluginIdentifier);
    
    public function getPluginIdentifier();
    
    public function setWidgetInstanceId($widgetInstanceId);
    
    public function getWidgetInstanceId();
    
    public function setWidgetIdentifier($widgetIdentifier);
    
    public function getWidgetIdentifier();
    
    public function setStrategyPanelName($name);
    
    public function getStrategyPanelName();
    
    public function setPosition($position);
    
    public function getPosition();
    
    public function setWidget(WidgetInterface $widget);
    
    public function getWidget();
        
    public function getWidgetInstance();
    
    public function getLabel();
}


