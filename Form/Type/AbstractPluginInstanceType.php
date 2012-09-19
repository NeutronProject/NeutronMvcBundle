<?php 
namespace Neutron\MvcBundle\Form\Type;

use Neutron\MvcBundle\Plugin\PluginInterface;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;

abstract class AbstractPluginInstanceType extends AbstractType
{
    protected $request;
    
    protected $plugin;
    
    protected $instanceType;
    
    protected $aclManager;
    
    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }
    
    public function setPlugin(PluginInterface $plugin)
    {
        $this->plugin = $plugin;
        return $this;
    }
    
    public function setInstanceType($instanceType)
    {
        $this->instanceType = $instanceType;
        return $this;
    }
    
    public function setAclManager($aclManager)
    {
        $this->aclManager = $aclManager;
        return $this;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('general', 'neutron_category');
        
        if ($this->instanceType){
            $builder->add('instance', $this->instanceType);
        }
        
        $builder->add('seo', 'neutron_seo');
        
        if (count($this->plugin->getPanels()) > 0){
            $builder->add('panels', 'neutron_panels', array(
                'plugin' => $this->plugin->getName(),
                'category' => (int) $this->request->get('id')
            ));
        }
        
        if ($this->aclManager->isAclEnabled()){
            $builder->add('acl', 'neutron_admin_form_acl_collection', array(
                'masks' => array(
                    'VIEW'     => 'View',
                ),
            ));
        }
        
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'cascade_validation' => true,
        ));
    }

}