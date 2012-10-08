<?php 
namespace Neutron\MvcBundle\Model;

use Neutron\MvcBundle\Widget\WidgetInterface;

use Neutron\MvcBundle\Plugin\PluginInterface;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Doctrine\Common\Persistence\ObjectManager;

interface MvcManagerInterface
{ 
    public function setObjectManager(ObjectManager $om);
    
    public function setAssetic(AsseticController $assetic);
    
    public function getPanelsForUpdate(PluginInterface $plugin, $pluginInstanceId, $pluginIdentifier);
    
    public function updatePanels($pluginInstanceId, array $panels);
    
    public function loadPanels(PluginInterface $plugin, $pluginInstanceId, $pluginIdentifier, $loadAssets = false);
    
    public function loadWidgetAssets(WidgetInterface $widget);
    
    public function getWidgetReferencesByPanel($pluginInstanceId, $pluginIdentifier, $strategyPanelName);
}