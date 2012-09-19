<?php
namespace Neutron\MvcBundle\Form\Handler;

use Neutron\TreeBundle\Model\TreeManagerFactoryInterface;

use Neutron\MvcBundle\Provider\PluginProviderInterface;

use Neutron\ComponentBundle\Form\Handler\AbstractFormHandler;

class CategoryHandler extends AbstractFormHandler
{ 
    protected $treeManager;
    protected $pluginProvider;
    protected $categoryClassName;

    public function __construct(PluginProviderInterface $pluginProvider, 
            TreeManagerFactoryInterface $treeManagerFactory, $categoryClass)
    {

        $this->pluginProvider = $pluginProvider;
        $this->treeManager = $treeManagerFactory->getManagerForClass($categoryClass);
    }
    
    protected function onSuccess()
    {
        $category = $this->form->getData();
        $this->treeManager->persistAsLastChildOf($category, $category->getParent());
        $plugin = $this->pluginProvider->get($this->form->getData()->getType());
        $pluginInstanceManager = $plugin->getManager();
        $pluginInstanceManager->create($category, true);
    }
    

    public function getRedirectUrl()
    {
        $category = $this->form->getData();
        $updateRoute = $this->pluginProvider
            ->get($category->getType())->getUpdateRoute();
        
        return $this->router->generate($updateRoute, array('id' => $category->getId()), true);
    }
   
}
