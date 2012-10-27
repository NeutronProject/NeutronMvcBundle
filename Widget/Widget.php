<?php 
namespace Neutron\MvcBundle\Widget;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Neutron\MvcBundle\Plugin\PluginInterface;

use Neutron\MvcBundle\Panel\PanelInterface;

use Neutron\MvcBundle\Model\Widget\WidgetManagerInterface;

class Widget implements WidgetInterface
{   
    protected $name;
    
    protected $label;
    
    protected $description;
     
    protected $administrationRoute;
    
    protected $frontControler;
    
    protected $forward;
    
    protected $manager;
    
    protected $pluginAware = false;
    
    protected $plugins = array();
    
    protected $panelAware = false;
    
    protected $panels = array();
    
    protected $javascriptAssets = array();
    
    protected $stylesheetAssets = array();
    
    protected $backendPages = array();


    public function __construct($name)
    {
        $this->setName($name);
    }
    
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }
    
    public function getName()
    {
        $this->validateProperty($this->name, 'name');
        return $this->name;
    }
    
    public function setLabel($label)
    {
        $this->label = (string) $label;
        return $this;
    }
   
    public function getLabel()
    {
        $this->validateProperty($this->label, 'label');
        return $this->label;
    }
    
    public function setDescription($description)
    {
        $this->description = (string) $description;
        return $this;
    }
    
    public function getDescription()
    {
        $this->validateProperty($this->description, 'description');
        return $this->description;
    }
     
    public function setAdministrationRoute($route)
    {
        $this->administrationRoute = (string) $route;
        return $this;
    }
    
    public function getAdministrationRoute()
    {
        return $this->administrationRoute;
    }
    
    public function setFrontController($controller)
    {
        $this->frontControler = (string) $controller;
        return $this;
    }
    
    public function getFrontController()
    {
        return $this->frontControler;
    }
    
    public function setForward($forward)
    {
        $this->forward = (string) $forward;
        return $this;
    }
    
    public function getForward()
    {
        return $this->forward;
    }
    
    public function setManager(WidgetManagerInterface $manager) 
    {
        $this->manager = $manager;
        return $this;
    }
    
    public function getManager() 
    {
        $this->validateProperty($this->manager, 'manager');
        return $this->manager;
    }
    
    public function enablePluginAware($bool)
    {
        $this->pluginAware = (bool) $bool;
        return $this;
    }
    
    public function isPluginAware()
    {
        return $this->pluginAware;
    }
    
    public function setAllowedPlugins(array $plugins)
    {
        $this->plugins = $plugins;
        return $this;
    }
    
    public function getAllowedPlugins()
    {
        return $this->plugins;
    }
    
    public function canUsePlugin(PluginInterface $plugin)
    {
        if ($this->pluginAware && !in_array($plugin->getName(), $this->getAllowedPlugins())){
            return false;
        } 
               
        return true;
    }
    
    public function enablePanelAware($bool)
    {
        $this->panelAware = (bool) $bool;
        return $this;
    }
    
    public function isPanelAware()
    {
        return $this->panelAware;
    }

    public function setAllowedPanels(array $panels)
    {
        $this->allowedPanels = $panels;
        return $this;
    }
    
    public function getAllowedPanels()
    {
        return $this->allowedPanels;
    }
    
    public function canUsePanel(PanelInterface $panel)
    {
        if ($this->panelAware && !in_array($panel->getName(), $this->getAllowedPanels())){
            return false;
        } 
               
        return true;
    }

    public function setJavascriptAssets(array $assets)
    {
        $this->javascriptAssets = $assets;
        return $this;
    }
    
    public function getJavascriptAssets()
    {
        return $this->javascriptAssets;
    }
    
    public function setStylesheetAssets(array $assets)
    {
        $this->stylesheetAssets = $assets;
        return $this;
    }
    
    public function exportOptions()
    {
        return array(
            'name'         => $this->getName(),
            'label'        => $this->getLabel(),
            'description'  => $this->getDescription(),
            'isPanelAware' => $this->isPanelAware(),
            'allowedPanels' => $this->getAllowedPanels() 
        );
    }


    public function getStylesheetAssets()
    {
        return $this->stylesheetAssets;
    }
    
    public function hasBackendPage($name)
    {
        return array_key_exists($name, $this->backendPages);
    }
    
    public function getBackendPage($name)
    {
        if (!$this->hasBackendPage($name)){
            throw new \InvalidArgumentException(sprintf('Backend page with name "%s" alreadt exists.', $name));
        }
    
        return $this->backendPages[$name];
    }
    
    public function addBackendPage(array $page)
    {
        $resolved = $this->resolvePage($page);
    
        $this->backendPages[$resolved['name']] = $resolved;
        return $this;
    }
    
    public function getBackendPages()
    {
        return $this->backendPages;
    }
    
    public function setBackendPages(array $pages)
    {
        foreach ($pages as $page){
            $this->addBackendPage($page);
        }
    
        return $this;
    }
    
    public function removeBackendPage($name)
    {
        unset($this->backendPages[$name]);
        return $this;
    }
    
    public function removeAllBackedPages()
    {
        $this->backendPages = array();
        return $this;
    }
    
    protected function resolvePage(array $page)
    {
        $resolver = new OptionsResolver();
    
        $resolver->setRequired(array(
            'name', 'label', 'route'
        ));
    
        $resolver->setDefaults(array('displayed' => false));
    
        $resolver->setAllowedTypes(array(
            'name'      => 'string',
            'label'     => 'string',
            'route'     => 'string',
            'displayed' => 'bool',
        ));
    
        return $resolver->resolve($page);
    }

    protected function validateProperty($value, $propertyName)
    {
        if (empty($value)){
            throw new \InvalidArgumentException(sprintf('Property "%s" is empty.', $propertyName));
        }
    }

}