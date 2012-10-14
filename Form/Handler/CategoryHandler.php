<?php
namespace Neutron\MvcBundle\Form\Handler;

use Neutron\MvcBundle\Provider\PluginProvider;

use Neutron\TreeBundle\Model\TreeManagerFactoryInterface;

use Neutron\MvcBundle\Provider\PluginProviderInterface;

use Neutron\ComponentBundle\Form\Handler\AbstractFormHandler;

class CategoryHandler extends AbstractFormHandler
{ 
    protected $treeManager;

    public function __construct(TreeManagerFactoryInterface $treeManagerFactory, $categoryClass)
    {
        $this->treeManager = $treeManagerFactory->getManagerForClass($categoryClass);
    }
    
    protected function onSuccess()
    {
        $category = $this->form->getData();
        $this->treeManager->persistAsLastChildOf($category, $category->getParent());
        $plugin = $this->container->get('neutron_mvc.plugin_provider')
            ->get($this->form->getData()->getType());

        $pluginManager = $this->container->get($plugin->getManagerServiceId());
        $pluginInstance = $pluginManager->create();
        $pluginInstance->setCategory($category);
        $pluginManager->update($pluginInstance, true);
    }
    

    public function getRedirectUrl()
    {
        $category = $this->form->getData();
        $plugin = $this->container->get('neutron_mvc.plugin_provider')
            ->get($category->getType());
        
        return $this->container->get('router')
            ->generate($plugin->getUpdateRoute(), array('id' => $category->getId()), true);
    }
   
}
