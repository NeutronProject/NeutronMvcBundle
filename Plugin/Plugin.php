<?php 
namespace Neutron\MvcBundle\Plugin;

use Neutron\MvcBundle\Model\MvcManagerInterface;

use Neutron\MvcBundle\Model\Plugin\PluginManagerInterface;

use Neutron\MvcBundle\Panel\PanelInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Plugin implements PluginInterface  
{   
    
    protected $name;
    
    protected $label; 
    
    protected $description;
    
    protected $administrationRoute;
    
    protected $updateRoute;
    
    protected $deleteRoute;
    
    protected $frontController;
    
    protected $manager;
    
    protected $managerServiceId;
    
    protected $panels = array();
    
    protected $treeOptions = array();
    
    protected $extraData = array();
    
    
    public function __construct($name)
    {
        $this->setName($name);
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
    
    public function setAdministrationRoute($route)
    {
        $this->administrationRoute = (string) $route;
        return $this;
    }
    
    public function getAdministrationRoute()
    {
        $this->validateProperty($this->administrationRoute, 'administrationRoute');
        return $this->administrationRoute;
    }
       
    public function setUpdateRoute($route)
    {
        $this->updateRoute = (string) $route;
        return $this;
    }
    
    public function getUpdateRoute()
    {
        $this->validateProperty($this->updateRoute, 'updateRoute');
        return $this->updateRoute;
    }
    

    
    public function setDeleteRoute($route)
    {
        $this->deleteRoute = (string) $route;
        return $this;
    }
    
    public function getDeleteRoute()
    {
        return $this->deleteRoute;
    }
    
    public function setFrontController($controller)
    {
        $this->frontController = (string) $controller;
        return $this;
    }
    
    public function getFrontController()
    {
        $this->validateProperty($this->frontController, 'frontController');
        return $this->frontController;
    }
    
    public function setManager(PluginManagerInterface $manager)
    {
        $this->manager = $manager;
        return $this;
    }
    
    public function getManager()
    {
        $this->validateProperty($this->manager, 'manager');
        return $this->manager;
    }
    
    public function setManagerServiceId($managerServicesId)
    {
        $this->managerServiceId = (string) $managerServicesId;
        return $this;
    }
    
    public function getManagerServiceId()
    {
        $this->validateProperty($this->managerServiceId, 'managerServiceId');
        return $this->managerServiceId;
    }
    
    public function hasPanel($name)
    {
        return array_key_exists($name, $this->panels);
    }
    
    public function getPanel($name)
    {
        if (!$this->hasPanel($name)){
            throw new \InvalidArgumentException(sprintf('Panel with name "%s" does not exist.', $name));
        }
        
        return $this->panels[$name];
    }
    
    public function addPanel(PanelInterface $panel)
    {
        if ($this->hasPanel($panel->getName())){
            throw new \InvalidArgumentException(sprintf('Panel with name "%s" alreadt exists.'));
        }
        
        $this->panels[$panel->getName()] = $panel;
        return $this;
    }
    
    public function setPanels(array $panels)
    {
        $this->removePanels();
        foreach ($panels as $panel){
            $this->addPanel($panel);
        }
        
        return $this;
    }
    
    public function getPanels()
    {
        return $this->panels;
    }
    
    public function removePanel($name)
    {
        unset($this->panels[$this->getPanel($name)->getName()]);
        return $this;
    }
    
    public function removePanels()
    {
        $this->panels = array();
        return $this;
    }
    
    public function setTreeOptions(array $options)
    {
        $this->treeOptions = $options;
        return $this;
    }
    
    public function getTreeOptions()
    {
        return $this->resolveTreeOptions($this->treeOptions);
    }
    
    public function setExtraData(array $data)
    {
        $this->extraData = $data;
        return $this;
    }
    
    public function getExtraData($key = null)
    {
        if ($key){
            if (isset($this->extraData[$key])){
                return $this->extraData[$key];
            } else {
                throw new \InvalidArgumentException(sprintf('Key: "%s" does not exist.', $key));
            }
        }
        
        return $this->extraData;
    }
    
    protected function validateProperty($value, $propertyName)
    {
        if (empty($value)){
            throw new \InvalidArgumentException(sprintf('Property "%s" is empty.', $propertyName));
        }
    }
    
    protected function resolveTreeOptions(array $options)
    {
        $resolver = new OptionsResolver();
    
        $resolver->setDefaults(array(
            'name' => $this->getName(),
            'children_strategy' => 'none',
            'start_drag' => true,
            'move_node' => true,
            'select_node' => true,
            'hover_node' => true,
            'disable_create_btn' => false,
            'disable_update_btn' => false,
            'disable_delete_btn' => false,
        ));
    
        $resolver->setAllowedTypes(array(
            'name' => 'string',
            'children_strategy' => 'string',
            'start_drag' => 'bool',
            'move_node' => 'bool',
            'select_node' => 'bool',
            'hover_node' => 'bool',
            'disable_create_btn' => 'bool',
            'disable_update_btn' => 'bool',
            'disable_delete_btn' => 'bool'
        ));
    
        $resolver->setAllowedValues(array(
            'children_strategy' => array('none', 'self', 'all')
        ));
    
        return $resolver->resolve($options);
    
    }
}