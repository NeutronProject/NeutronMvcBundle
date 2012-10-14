<?php 
namespace Neutron\MvcBundle\Doctrine;

use Neutron\Bundle\AsseticBundle\Controller\AsseticController;

use Neutron\MvcBundle\Model\MvcManagerInterface;

use Neutron\MvcBundle\Widget\WidgetInterface;

use Neutron\MvcBundle\Plugin\PluginInterface;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Doctrine\Common\Persistence\ObjectManager;

class MvcManager implements MvcManagerInterface 
{
    
    const WIDGET_REFERENCE = 'Neutron\\MvcBundle\\Entity\\WidgetReference';
    
    protected $om;
    
    protected $assetic;
    
    protected $widgetReferenceRepository;
    
    protected $snapshot = array();
    
    public function setObjectManager(ObjectManager $om)
    {   
        $this->om = $om;
    }
    
    public function setAssetic(AsseticController $assetic)
    {
        $this->assetic = $assetic;
    }
    
    public function getWidgetReferencesByPanel($pluginInstanceId, $pluginIdentifier, $strategyPanelName)
    {
        return $this->getWidgetReferenceRepository()->getWidgetReferencesByPanel(
            $pluginInstanceId, $pluginIdentifier, $strategyPanelName
        );
    }
    
    public function getPanelsForUpdate(PluginInterface $plugin, $pluginInstanceId, $pluginIdentifier)
    {
        $panels = array();
        foreach ($plugin->getPanels() as $panel){
            $widgetReferences =
                $this->getWidgetReferencesByPanel($pluginInstanceId, $pluginIdentifier, $panel->getName());
            $this->takeSnapshot($widgetReferences);
            $panel->setWidgetReferences($widgetReferences);
            $panels[$panel->getName()] = $panel;
        }
         
        return $panels;
    }
    
    public function updatePanels($pluginInstanceId, array $panels, $andFlush = false)
    {
        foreach ($panels as $panel){
            foreach ($panel->getWidgetReferences() as $widgetReference){
                $widgetReference->setPluginInstanceId((int) $pluginInstanceId);
                $hash = \spl_object_hash($widgetReference);
                if (array_key_exists($hash, $this->snapshot)){
                    unset($this->snapshot[$hash]);
                }
                $this->om->persist($widgetReference);
            }
        }
    
        $this->removeScheduledForDeletionWidgetReferences();
        
        if ($andFlush){
            $this->om->flush();
        }
        
    }
    
    public function loadPanels(PluginInterface $plugin, $pluginInstanceId, $pluginIdentifier, $loadAssets = false)
    {
        foreach ($plugin->getPanels() as $panel){
             
            if ($panel->isInitialized()){
                continue;
            }
    
            $widgetReferences =
                $this->getWidgetReferencesByPanel($pluginInstanceId, $pluginIdentifier, $panel->getName());

            $panel->setWidgetReferences($widgetReferences);
            $panel->initialize(true);
            if($loadAssets === true){
                foreach ($widgetReferences as $widgetReference){
                    $this->loadWidgetAssets($widgetReference->getWidget());
                }
            }
        }
    
        return $this;
    }
    
    public function loadWidgetAssets(WidgetInterface $widget)
    {
        foreach ($widget->getJavascriptAssets() as $javascript){
            $this->assetic->appendJavascript($javascript);
        }
    
        foreach ($widget->getStylesheetAssets() as $stylesheet){
            $this->assetic->appendStylesheet($stylesheet);
        }
    
        return $this;
    }
    
    public function getWidgetReferenceRepository()
    {
        if (null === $this->widgetReferenceRepository){
            return $this->widgetReferenceRepository = $this->om->getRepository(self::WIDGET_REFERENCE);
        }
        
        return $this->widgetReferenceRepository;
    }
   
    protected function takeSnapshot(array $widgetReferences)
    {
        foreach ($widgetReferences as $widgetReference){
            $this->snapshot[\spl_object_hash($widgetReference)] = $widgetReference;
        }
    }
    
    protected function removeScheduledForDeletionWidgetReferences()
    {
        foreach ($this->snapshot as $entity){
            $this->om->remove($entity);
        }
    } 
}