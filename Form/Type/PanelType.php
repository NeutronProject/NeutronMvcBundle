<?php 
namespace Neutron\MvcBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;

class PanelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('widgetReferences', 'collection', array(
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype'    => true,
                'type'         => 'neutron_widget_reference',
            ))
        ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Neutron\MvcBundle\Panel\Panel'
        ));
    }
    
    public function getName()
    {
        return 'neutron_panel';    
    }
}