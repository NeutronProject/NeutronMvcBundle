<?php 
namespace Neutron\MvcBundle\Doctrine;

use Neutron\MvcBundle\Widget\WidgetInterface;

use Neutron\MvcBundle\Plugin\PluginInterface;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Neutron\MvcBundle\Model\Plugin\PluginManagerInterface;

use Doctrine\Common\Persistence\ObjectManager;

class AbstractPluginManager implements PluginManagerInterface  
{
    
    const WIDGET_REFERENCE = 'Neutron\\MvcBundle\\Entity\\WidgetReference';
    
    protected $om;
    
    protected $pluginInstanceClassName;
    
    protected $repository;
    
    protected $plugin;
    
    protected $widgetReferenceRepository;
    
    protected $snapshot = array();
    
    public function setPluginInstanceClassName($pluginInstanceClassName)
    {   
        $this->pluginInstanceClassName = (string) $pluginInstanceClassName;
        $this->repository = $this->om->getRepository($this->pluginInstanceClassName);
        $this->widgetReferenceRepository = $this->om->getRepository(self::WIDGET_REFERENCE);
        return $this;
    }
    
    public function setObjectManager(ObjectManager $om)
    {   
        $this->om = $om;
        return $this;
    }
    
    public function setPlugin(PluginInterface $plugin)
    {
        $this->plugin = $plugin;
        return $this;
    }
    
    public function getPlugin()
    {
        return $this->plugin;
    }
    
    public function create(CategoryInterface $category, $andFlush = false)
    {
        $class = $this->pluginInstanceClassName;
        $entity = new $class();
        
        $this->validateEntity($entity);
        $entity->setCategory($category);
        
        if ($andFlush){
            $this->om->persist($entity);
            $this->om->flush();
        }
    
        return $entity;
    }
    
    public function update($entity, $andFlush = false)
    {
        $this->validateEntity($entity);
        $this->om->persist($entity);
        if ($andFlush){
            $this->om->flush();
        }
    }
    
    public function delete($entity, $andFlush = false)
    {
        $this->validateEntity($entity);
        $this->om->remove($entity);
    
        if ($andFlush){
            $this->om->flush();
        }
    }
    
    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }
    
    public function findBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }
    
    public function getWidgetReferencesByPanel($category, $strategyPanelName)
    {
        return $this->widgetReferenceRepository->getWidgetReferencesBypanel(
            $category, $this->getPlugin()->getName(), $strategyPanelName
        );
    }
    
    public function getPanelsForUpdate($category)
    {
        $panels = array();
        foreach ($this->getPlugin()->getPanels() as $panel){
            $widgetReferences =
                $this->getWidgetReferencesByPanel($category, $panel->getName());
            $this->takeSnapshot($widgetReferences);
            $panel->setWidgetReferences($widgetReferences);
            $panels[$panel->getName()] = $panel;
        }
         
        return $panels;
    }
    
    public function updatePanels(array $panels, $andFlush = false)
    {
        foreach ($panels as $panel){
            foreach ($panel->getWidgetReferences() as $widgetReference){
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
    
    public function loadPanels($category, $loadAssets = false)
    {
        foreach ($this->getPlugin()->getPanels() as $panel){
             
            if ($panel->isInitialized()){
                continue;
            }
    
            $widgetReferences =
                $this->getWidgetReferencesByPanel($category, $panel->getName());
    
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
        $assetic = $this->container->get('neutron_assetic');
    
        foreach ($widget->getJavascriptAssets() as $javascript){
            $assetic->appendJavascript($javascript);
        }
    
        foreach ($widget->getStylesheetAssets() as $stylesheet){
            $assetic->appendStylesheet($stylesheet);
        }
    
        return $this;
    }
    
    
    public function getRepository()
    {
        return $this->repository;
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
    
    protected function validateEntity($entity)
    {
        $class = get_class($entity);
    
        if ($class !== $this->pluginInstanceClassName){
            throw new \InvalidArgumentException(
                sprintf('Entity "%s" must be instance of "%s"', $class, $this->pluginInstanceClassName)
            );
        }
    }
}