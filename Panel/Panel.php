<?php 
namespace Neutron\MvcBundle\Panel;

use Neutron\MvcBundle\Model\Widget\WidgetReferenceInterface;

class Panel implements PanelInterface  
{ 
    
    protected $name;
    
    protected $label;
    
    protected $description; 
    
    protected $widgetReferences = array();
    
    protected $isInitialized = false;
    
    public function __construct($name, $options = array())
    {
        $this->setName($name);
        isset($options['label']) ? $this->setLabel($options['label']) : null ;
        isset($options['description']) ? $this->setDescription($options['description']) : null ;
    }
    
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }
    
    public function getName()
    {
        $this->validateProperty($this->name, 'name');
        return $this->name;
    }
    
    public function setLabel($label)
    {
        $this->label = (string) $label;
        return $this;
    }
    
    public function getLabel()
    {
        $this->validateProperty($this->label, 'label');
        return $this->label;
    }
    
    public function setDescription($description)
    {
        $this->description = (string) $description;
        return $this;
    }
    
    public function getDescription()
    {
        $this->validateProperty($this->description, 'description');
        return $this->description;
    }
    
    public function hasWidgetReference($identifier)
    {
        return isset($this->widgetReferences[$identifier]);
    }
    
    public function getWidgetReference($identifier)
    {
       if ($this->hasWidgetReference($identifier)){
           return $this->widgetReferences[$identifier];
       }      
    }
    
    public function addWidgetReference(WidgetReferenceInterface $widgetReference)
    {
        if(!$this->hasWidgetReference($widgetReference->getIdentifier())){
            $this->widgetReferences[$widgetReference->getIdentifier()] = $widgetReference;
        }
        
        return $this;
    }

    public function setWidgetReferences(array $widgetReferences)
    {
        $this->removeWidgetReferences();
        
        foreach ($widgetReferences as $widgetReference){
            $this->addWidgetReference($widgetReference);
        }
        
        return $this;
    }
    
    public function getWidgetReferences()
    {
        return $this->widgetReferences;
    }
    
    public function removeWidgetReference(WidgetReferenceInterface $widgetReference)
    {
        if($this->hasWidgetReference($widgetReference->getIdentifier())){
            unset($this->widgetReferences[$widgetReference->getIdentifier()]);
        }
        
        return $this;
    }
    
    public function removeWidgetReferences()
    {
        $this->widgetReferences = array();
        return $this;
    }

    public function initialize($bool)
    {
        $this->isInitialized = (bool) $bool;
        return $this;
    }
    
    public function isInitialized()
    {
        return $this->isInitialized;
    }
    
    public function exportOptions()
    {
        return array(
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'description' => $this->getDescription(),
        );
    }


    protected function validateProperty($value, $propertyName)
    {
        if (empty($value)){
            throw new \InvalidArgumentException(sprintf('Property "%s" is empty.', $propertyName));
        }
    }
}