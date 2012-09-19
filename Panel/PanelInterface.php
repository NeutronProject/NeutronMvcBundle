<?php
namespace Neutron\MvcBundle\Panel;

use Neutron\MvcBundle\Model\Widget\WidgetReferenceInterface;

interface PanelInterface
{   
    public function setName($name);
    
    public function getName();
    
    public function setLabel($label);
    
    public function getLabel();
    
    public function setDescription($description);
    
    public function getDescription();
    
    public function hasWidgetReference($name);
    
    public function getWidgetReference($name);
    
    public function addWidgetReference(WidgetReferenceInterface $widgetReference);

    public function setWidgetReferences(array $widgetReferences);
    
    public function getWidgetReferences();
    
    public function removeWidgetReference(WidgetReferenceInterface $widgetReference);
    
    public function removeWidgetReferences();

    public function initialize($bool);
  
    public function isInitialized();
    
    public function exportOptions();
}