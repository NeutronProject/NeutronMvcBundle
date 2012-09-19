<?php
namespace Neutron\MvcBundle\Widget;

use Neutron\MvcBundle\Plugin\PluginInterface;

use Neutron\MvcBundle\Panel\PanelInterface;

use Neutron\MvcBundle\Model\Widget\WidgetManagerInterface;

interface WidgetInterface
{
    
    public function setName($name);
    
    public function getName();
    
    public function setLabel($label);
    
    public function getLabel();
    
    public function setDescription($description);
    
    public function getDescription();
     
    public function setAdministrationRoute($route);
    
    public function getAdministrationRoute();
    
    public function setFrontController($controller);
    
    public function getFrontController();
    
    public function setManager(WidgetManagerInterface $manager);
    
    public function getManager();
    
    public function enablePluginAware($bool);
    
    public function isPluginAware();
    
    public function setAllowedPlugins(array $plugins);
    
    public function getAllowedPlugins();
    
    public function canUsePlugin(PluginInterface $plugin);
    
    public function enablePanelAware($bool);

    public function isPanelAware();

    public function setAllowedPanels(array $panels);
    
    public function getAllowedPanels();
    
    public function canUsePanel(PanelInterface $panel);

    public function setJavascriptAssets(array $assets);
    
    public function getJavascriptAssets();
    
    public function setStylesheetAssets(array $assets);
    
    public function getStylesheetAssets();
    
    public function exportOptions();

}