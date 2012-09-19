<?php 
namespace Neutron\MvcBundle\Model\Plugin;

use Neutron\MvcBundle\Widget\WidgetInterface;

use Neutron\MvcBundle\Plugin\PluginInterface;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Doctrine\Common\Persistence\ObjectManager;

interface PluginManagerInterface 
{ 
    public function setPluginInstanceClassName($className);
    
    public function setObjectManager(ObjectManager $om);
    
    public function create(CategoryInterface $category, $andFlush = false); 

    public function update($entity, $andFlush = true);
    
    public function delete($entity, $andFlush = true);
    
    public function findOneBy(array $criteria);
    
    public function findBy(array $criteria);
    
    public function getRepository();
    
    public function setPlugin(PluginInterface $plugin);
    
    public function getPlugin();
    
    public function getPanelsForUpdate($category);
    
    public function updatePanels(array $panels);
    
    public function loadPanels($category, $loadAssets = false);
    
    public function loadWidgetAssets(WidgetInterface $widget);
    
    public function getWidgetReferencesByPanel($category, $strategyPanelName);
}