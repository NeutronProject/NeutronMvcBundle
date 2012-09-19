<?php
namespace Neutron\MvcBundle\Controller\Backend;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class WidgetController extends Controller
{
    public function instancesAction()
    {
        $name = $this->getRequest()->get('name');       
        $widget = $this->get('neutron_mvc.widget_provider')->get($name);
        $manager = $widget->getManager();
        $locale = $this->container->get('session')
            ->get('frontend_language', $this->container->getParameter('locale'));
        $instances = $manager->getInstances($locale);
        
        if (!empty($instances)){
            foreach ($instances as $instance){
                $this->resolveInstances($instance);
            }
        }
        
        return new Response(\json_encode($instances));
    }
    
    protected function resolveInstances(array $instance)
    {
        $resolver = new OptionsResolver();
        
        $resolver->setRequired(array('identifier', 'label'));
        $resolver->setAllowedTypes(array(
            'identifier'  => array('string'),        
            'label'       => array('string'),        
        ));
        
        $resolver->resolve($instance);
    }
}