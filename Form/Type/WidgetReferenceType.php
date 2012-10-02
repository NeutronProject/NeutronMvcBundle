<?php 
namespace Neutron\MvcBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;

class WidgetReferenceType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pluginInstanceId', 'hidden')
            ->add('pluginIdentifier', 'hidden')
            ->add('widgetInstanceId', 'hidden')
            ->add('widgetIdentifier', 'hidden')
            ->add('strategyPanelName', 'hidden')
            ->add('position', 'hidden')           
        ;
    }
        
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Neutron\MvcBundle\Entity\WidgetReference'        
        ));
    }
    
    public function getName()
    {
        return 'neutron_widget_reference';    
    }
}