<?php 
namespace Neutron\MvcBundle\Form\Type;

use Neutron\MvcBundle\Provider\PluginProviderInterface;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormInterface;

class CategoryType extends AbstractType
{
    
    protected $subscriber;
    
    protected $class;
    
    protected $pluginProvider;
    
    public function __construct(EventSubscriberInterface $subscriber, $class, 
            PluginProviderInterface $pluginProvider)
    {
        $this->subscriber = $subscriber;
        $this->class = $class;
        $this->pluginProvider = $pluginProvider;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'label' => 'form.title',
                'translation_domain' => 'NeutronMvcBundle'
            ))
            
            ->add('name', 'text', array(
                'label' => 'form.name',
                'translation_domain' => 'NeutronMvcBundle'
            ))
            
            ->add('slug', 'text', array(
                'label' => 'form.slug',
                'translation_domain' => 'NeutronMvcBundle'
            ))        
            
            ->add('linkTarget', 'choice', array(
                'choices' => array('_self' => 'from.target.self', '_blank' => 'form.target.blank'),
                'preferred_choices' => array('_self' => 'from.target.self'),
                'multiple' => false,
                'expanded' => false,
                'attr' => array('class' => 'uniform'),
                'label' => 'form.link_target',
                'translation_domain' => 'NeutronMvcBundle'
            ))
            
            ->add('type', 'choice', array(
		        'choices' => $this->pluginProvider->getAsOptions(),
		        'multiple' => false,
		        'expanded' => false,
		        'attr' => array('class' => 'uniform'),
		        'label' => 'form.type',
                'empty_value' => 'form.empty_value',
                'translation_domain' => 'NeutronMvcBundle'
		    ))
            
            ->add('enabled', 'checkbox', array(
                'label' => 'form.enabled', 
                'value' => 1,
                'required' => true,
                'translation_domain' => 'NeutronMvcBundle'
            ))
            
            ->add('displayed', 'checkbox', array(
                'label' => 'form.displayed', 
                'value' => 1,
                'required' => false,
                'translation_domain' => 'NeutronMvcBundle'
            ))
            
        ;
        
        $builder->addEventSubscriber($this->subscriber);
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'csrf_protection' => false,
            'validation_groups' => function(FormInterface $form){
                $data = $form->getData();
                
                $validationGroups = array();
                
                if ($data->getId()){
                    $validationGroups = array_merge($validationGroups, array('update'));
                } else {
                    $validationGroups = array_merge($validationGroups, array('create'));
                }

                return $validationGroups;
            },
        ));
    }
    
    public function getName()
    {
        return 'neutron_category';
    }
    
}