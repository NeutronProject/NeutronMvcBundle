<?php  
namespace Neutron\MvcBundle\EventListener;

use Knp\Menu\ItemInterface;

use Neutron\MvcBundle\Provider\WidgetProviderInterface;

use Neutron\MvcBundle\Provider\PluginProviderInterface;

use Neutron\AdminBundle\Menu\Main;

use Knp\Menu\FactoryInterface;

use Neutron\AdminBundle\Event\ConfigureMenuEvent;

class AdminMenuListener
{
   
    protected $pluginProvider;
    
    protected $widgetProvider;
    
    public function __construct(PluginProviderInterface $pluginProvider, WidgetProviderInterface $widgetProvider)
    {
        $this->pluginProvider = $pluginProvider;
        $this->widgetProvider = $widgetProvider;
    }
    
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        
        if ($event->getIdentifier() !== Main::IDENTIFIER){
            return;
        }
        
        $root = $event->getMenu()->getRoot();
        $factory = $event->getFactory();
        
        $this->createPluginsMenu($root);
        $this->createWidgetsMenu($root);

    }
    
    protected function createPluginsMenu(ItemInterface $root)
    {
        $pluginMenu = $root->addChild('plugins', array(
            'label' => 'menu.plugins',
            'uri' => 'javascript:;',
            'attributes' => array(
                'class' => 'dropdown',
            ),
            'childrenAttributes' => array(
                'class' => 'menu',
            ),
            'extras' => array(
                'safe_label' => true,
                'breadcrumbs' => true,
                'translation_domain' => 'NeutronMvcBundle'
            ),
        ));
        
        foreach ($this->pluginProvider->all() as $plugin){
            
            if (count($plugin->getBackendPages()) == 0){
                continue;
            }
            
            $pluginMenu->addChild($plugin->getName(), array(
                'label' => $plugin->getLabel(),
                'extras' => array(
                    'section' => true,
                ),
            ));
            
            foreach($plugin->getBackendPages() as $backendPage){
                $pluginMenu->addChild($backendPage['name'], array(
                    'label' => $backendPage['label'],
                    'route' => $backendPage['route'],
                    'extras' => array(
                        'breadcrumbs' => true,
                    ),
                ));
            } 
        }
        
        $pluginMenu->moveToPosition(2);
    }
    
    protected function createWidgetsMenu(ItemInterface $root)
    {
        $widgetMenu = $root->addChild('widgets', array(
            'label' => 'menu.widgets',
            'uri' => 'javascript:;',
            'attributes' => array(
                'class' => 'dropdown',
            ),
            'childrenAttributes' => array(
                'class' => 'menu',
            ),
            'extras' => array(
                'safe_label' => true,
                'breadcrumbs' => true,
                'translation_domain' => 'NeutronMvcBundle'
            ),
        ));
        
        foreach ($this->widgetProvider->all() as $widget){
            
            $widgetMenu->addChild($widget->getName(), array(
                'label' => $widget->getLabel(),
                'route' => $widget->getAdministrationRoute(),
                'extras' => array(
                    'breadcrumbs' => true,
                ),
            ));
        }
        
        $widgetMenu->moveToPosition(3);
    }

    
}