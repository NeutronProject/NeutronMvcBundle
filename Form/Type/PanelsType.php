<?php 
namespace Neutron\MvcBundle\Form\Type;

use Neutron\MvcBundle\Provider\PluginProviderInterface;

use Neutron\MvcBundle\Provider\WidgetProviderInterface;

use Neutron\Bundle\AsseticBundle\Controller\AsseticController;

use Symfony\Component\Form\FormView;

use Symfony\Component\Form\FormInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\OptionsResolver\Options;

use Symfony\Component\Routing\Router;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;

class PanelsType extends AbstractType
{

    protected $pluginProvider;
    
    protected $widgetProvider;
    
    protected $assetic;
    
    protected $router;
    
    protected $translator;


    public function __construct(PluginProviderInterface $pluginProvider, WidgetProviderInterface $widgetProvider, 
            AsseticController $assetic, Router $router, Translator $translator)
    {
        $this->pluginProvider = $pluginProvider;
        $this->widgetProvider = $widgetProvider;  
        $this->assetic = $assetic; 
        $this->router = $router;
        $this->translator = $translator;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {   
        $plugin = $this->pluginProvider->get($options['plugin']);
        
        foreach ($plugin->getPanels() as $panel){
            $builder->add($panel->getName(), 'neutron_panel', array('label' => $panel->getLabel()));
        } 
    }
    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $this->assetic->appendStylesheet('bundles/neutronmvc/css/panels.css');
        $this->assetic->appendJavascript('bundles/neutronmvc/js/panels.js');
        
        $plugin = $this->pluginProvider->get($options['plugin']);
        $view->vars['widgets'] = $this->widgetProvider->getAvailableWidgets($plugin);
        $view->vars['configs'] = $options['opts'];
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $router = $this->router;
        $translator = $this->translator;
        
        $resolver->setDefaults(array(
            'configs' => array(),
            'opts' => function(Options $options) use ($router, $translator){
                $configs = $options->get('configs');
                $opts = array(
                    'instances_empty_value' => $translator->trans('instances.empty_value', array(), 'NeutronMvcBundle'),
                    'widget_instance_url' => 
                        $router->generate('neutron_mvc.widget_intances'),
                    'plugin' => $options->get('plugin'),
                    'category' => $options->get('category'),
                );
                
                $options = \array_replace_recursive($opts, $configs);
                return $options;
            },
        ));
            
        $resolver->setRequired(array('plugin', 'category'));
        $resolver->setAllowedTypes(array('plugin' => array('string'), 'category' => array('string', 'integer')));
    }
    
    public function getName()
    {
        return 'neutron_panels';
    }
    
}