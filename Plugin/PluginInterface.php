<?php
namespace Neutron\MvcBundle\Plugin;

use Neutron\ComponentBundle\Model\ManagerInteface;

use Neutron\MvcBundle\Panel\PanelInterface;

interface PluginInterface
{   
    const CHILDREN_STRATEGY_SELF = 'self';
    
    const CHILDREN_STRATEGY_NONE = 'none';
    
    const CHILDREN_STRATEGY_ALL = 'all';
    
    public function setName($name);
    
    public function getName();
    
    public function setLabel($label);
    
    public function getLabel();
    
    public function setDescription($description);
    
    public function getDescription();   

    public function setAdministrationRoute($route);
    
    public function getAdministrationRoute();
    
    public function setUpdateRoute($route);
    
    public function getUpdateRoute();
    
    public function setDeleteRoute($route);
    
    public function getDeleteRoute();
    
    public function setFrontController($controller);
    
    public function getFrontController();
    
    public function setManager(ManagerInteface $manager);
    
    public function getManager();
    
    public function getPanel($name);
    
    public function hasPanel($name);
    
    public function addPanel(PanelInterface $panel);
    
    public function setPanels(array $panels);
    
    public function getPanels();
    
    public function removePanel($name);
    
    public function removePanels();
    
    public function setTreeOptions(array $options);
    
    public function getTreeOptions();
    
    public function setExtraData(array $data);
    
    public function getExtraData($key = null);
}