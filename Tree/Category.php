<?php 
namespace Neutron\MvcBundle\Tree;

use Neutron\AdminBundle\Helper\ApplicationHelper;

use Neutron\MvcBundle\Provider\PluginProviderInterface;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Neutron\TreeBundle\Tree\FactoryInterface;

class Category
{
    protected $categoryClass;
    
    protected $factory;
    
    protected $router;
    
    protected $translator;
    
    protected $pluginProvider;
    
    protected $applicationHelper;
    
    public function __construct($categoryClass, FactoryInterface $factory, Router $router,  
            Translator $translator, PluginProviderInterface $pluginProvider, ApplicationHelper $applicationHelper)
    {
        $this->categoryClass = $categoryClass;
        $this->factory = $factory;
        $this->router = $router;
        $this->translator = $translator;
        $this->pluginProvider = $pluginProvider;
        $this->applicationHelper = $applicationHelper;
    }
    
    public function create()
    {

        $tree = $this->factory->createTree('category');
        $tree
            ->setManager($this->factory->createManager($this->categoryClass)
                ->setLocale($this->applicationHelper->getFrontLocale())
            )
            ->addPlugin($this->factory->createPlugin('ui', array('selectLimit' => 1)))
            ->addPlugin($this->factory->createPlugin('contextmenu', array(
                'createBtnOptions' => array(
                    'disabled' => false,
                    'label' => $this->translator->trans('category.btn.create', array(), 'NeutronMvcBundle'),
                    'uri' => $this->router->generate('neutron_mvc.category.create', array('parentId' => '{parentId}'))        
                ), 
                'updateBtnOptions' => array(
                    'disabled' => false,
                    'label' => $this->translator->trans('category.btn.update', array(), 'NeutronMvcBundle'),
                    'uri' => $this->router->generate('neutron_mvc.category.update', array('nodeId' => '{nodeId}'))        
                ), 
                'deleteBtnOptions' => array(
                    'disabled' => false,
                    'label' => $this->translator->trans('category.btn.delete', array(), 'NeutronMvcBundle'),
                    'uri' => $this->router->generate('neutron_mvc.category.delete', array('nodeId' => '{nodeId}'))        
                ), 
            )))
            ->addPlugin($this->factory->createPlugin('dnd'))
            ->addPlugin($this->factory->createPlugin('crrm'))
            ->addPlugin($this->factory->createPlugin('themes'))
            ->addPlugin($this->factory->createPlugin('cookies'))
            ->addPlugin($this->factory->createPlugin('types', $this->pluginProvider->getTreeOptions()))
        ;
        
        return $tree;
    }
}