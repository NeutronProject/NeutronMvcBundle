<?php 
/*
 * This file is part of NeutronMvcBundle
 *
 * (c) Zender <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\MvcBundle\Twig\Extension;

use Symfony\Bundle\TwigBundle\TwigEngine;

use Neutron\MvcBundle\Provider\WidgetProvider;

use Neutron\MvcBundle\Provider\PluginProvider;

use Neutron\MvcBundle\Model\Widget\WidgetReferenceInterface;

use Symfony\Component\DependencyInjection\Container;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\HttpKernel;

use Symfony\Component\HttpKernel\HttpKernelInterface;

use Neutron\MvcBundle\Panel\PanelInterface;

use Neutron\MvcBundle\Provider\PluginProviderInterface;

use Neutron\MvcBundle\Provider\WidgetProviderInterface;

/**
 * Twig extension
 *
 * @author Zender <azazen09@gmail.com>
 * @since 1.0
 */
class MvcExtension extends \Twig_Extension
{

    protected $container;
    
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getPlugin($name)
    {
        return $this->container->get('neutron_mvc.plugin_provider')->get($name);
    }
    
    public function getWidget($name)
    {
        return $this->container->get('neutron_mvc.widget_provider')->get($name);
    }
    
    public function renderPanel(PanelInterface $panel)
    {  
        $html = '';
        
        foreach ($panel->getWidgetReferences() as $reference){
            
            $widget = $reference->getWidget();
            
            $response = $this->container->get('http_kernel')->forward(
                $widget->getFrontController(), array(
                    'widgetInstanceId' => $reference->getWidgetInstanceId()
                )
            );
            
            $html .= $response->getContent();           
        }
        
        return $html;
    }
    
    public function renderWidget(WidgetReferenceInterface $widgetReference = null)
    {
        
        if (!$widgetReference){
            return null;
        }
        
        $widget = $widgetReference->getWidget();
        $response = $this->container->get('http_kernel')->forward(
            $widget->getFrontController(), array(
                'widgetInstanceId' => $widgetReference->getWidgetInstanceId()
            )
        );
        
        return $response->getContent();
    }
    
    public function generateUrl($pluginName, $id)
    {   
        $plugin = $this->getPlugin($pluginName);
        $entity = $plugin->getManager()->get($id); 
        
        if (!$entity){
            return;
        }
        
        $category = $entity->getCategory();
        
        if(!$category->isDisplayed() || !$category->isEnabled()){
            return;
        }
        
        $slug = $category->getSlug();
     
        return $this->container->get('router')
            ->generate('neutron_mvc.distributor', array('slug' => $slug));
    }

    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return array(
            'neutron_mvc_plugin' =>
                new \Twig_Function_Method($this, 'getPlugin'),
            'neutron_mvc_widget' =>
                new \Twig_Function_Method($this, 'getWidget'),
            'neutron_panel_render' =>
                new \Twig_Function_Method($this, 'renderPanel', array('is_safe' => array('html'))),
            'neutron_widget_render' =>
                new \Twig_Function_Method($this, 'renderWidget', array('is_safe' => array('html'))),
            'neutron_path' => 
                new \Twig_Function_Method($this, 'generateUrl'),
        );
    }

    /**
     * (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'neutron_mvc_extension';
    }

}
