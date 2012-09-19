<?php 
namespace Neutron\MvcBundle\Model\Widget;

use Neutron\MvcBundle\Widget\WidgetInterface;

interface WidgetReferenceInterface
{
    public function setIdentifier($identifier);
    
    public function getIdentifier();
    
    public function setCategory($category);
    
    public function getCategory();
    
    public function setStrategyWidgetName($name);
    
    public function getStrategyWidgetName();
    
    public function setStrategyPluginName($name);
    
    public function getStrategyPluginName();
    
    public function setStrategyPanelName($name);
    
    public function getStrategyPanelName();
    
    public function setPosition($position);
    
    public function getPosition();
    
    public function setWidget(WidgetInterface $widget);
    
    public function getWidget();
        
    public function getWidgetInstance();
    
    public function getLabel();
}


