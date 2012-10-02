<?php 
namespace Neutron\MvcBundle\Doctrine\EventSubscriber;

use Neutron\MvcBundle\Model\Plugin\PluginInstanceInterface;

use Doctrine\ORM\EntityManager;

use Neutron\MvcBundle\Model\Widget\WidgetInstanceInterface;

use Doctrine\ORM\Event\OnFlushEventArgs;

use Doctrine\ORM\Event\PostFlushEventArgs;

use Neutron\MvcBundle\Provider\WidgetProvider;

use Symfony\Component\DependencyInjection\Container;

use Neutron\MvcBundle\Model\Widget\WidgetReferenceInterface;

use Doctrine\ORM\Event\LifecycleEventArgs;

use Doctrine\ORM\Events;

use Doctrine\Common\EventSubscriber;

use Neutron\MvcBundle\Provider\WidgetProviderInterface;

class WidgetStrategyEventSubscriber implements EventSubscriber
{
    protected $container;
    
    protected $scheduledForDeletionWidgetReferencesByPluginIdentifier = array();
    
    protected $scheduledForDeletionWidgetReferencesByWidgetIdentifier = array();
    
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        
        foreach ($uow->getScheduledEntityDeletions() as $entity) {
        
            if ($entity instanceof PluginInstanceInterface){ 
                $this->scheduledForDeletionWidgetReferencesByPluginIdentifier[] = array(
                    'pluginInstanceId' => $entity->getId(),
                    'pluginIdentifier' => $entity->getIdentifier()
                );
            }
            
            if ($entity instanceof WidgetInstanceInterface){ 
                $this->scheduledForDeletionWidgetReferencesByWidgetIdentifier[] =array(
                    'widgetInstanceId' => $entity->getId(),
                    'widgetIdentifier' => $entity->getIdentifier()       
                );
            }
        }
    }
    
    public function postFlush(PostFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        
        if (count($this->scheduledForDeletionWidgetReferencesByPluginIdentifier) > 0){
            
            foreach ($this->scheduledForDeletionWidgetReferencesByPluginIdentifier as $data){
                $this->deleteWidgetReferencesByPluginIdentifier($em, $data['pluginIdentifier'], $data['pluginInstanceId']);
            }   

            $this->scheduledForDeletionWidgetReferencesByPluginIdentifier = array();
        }     
        
        if (count($this->scheduledForDeletionWidgetReferencesByWidgetIdentifier) > 0){
            
            foreach ($this->scheduledForDeletionWidgetReferencesByWidgetIdentifier as $data){
                $this->deleteWidgetReferencesByWidgetIdentifier($em, $data['widgetIdentifier'], $data['widgetInstanceId']);
            }      

            $this->scheduledForDeletionWidgetReferencesByWidgetIdentifier = array();
        }     
    }
    
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        
        if ($entity instanceof WidgetReferenceInterface){
            $widget = $this->container->get('neutron_mvc.widget_provider')
                        ->get($entity->getWidgetIdentifier());
            
            $entity->setWidget($widget);
        }
    }
    
    public function getSubscribedEvents()
    {
        return array(Events::onFlush, Events::postFlush, Events::postLoad);
    }
    

    protected function deleteWidgetReferencesByPluginIdentifier(EntityManager $em, $pluginIdentifier, $pluginInstanceId)
    {
        $query = $em->createQuery('DELETE FROM Neutron\\MvcBundle\\Entity\\WidgetReference w WHERE w.pluginIdentifier = ?1 AND w.pluginInstanceId = ?2');
        $query->setParameters(array(1 => $pluginIdentifier, 2 => $pluginInstanceId))->execute();
    }
    
    protected function deleteWidgetReferencesByWidgetIdentifier(EntityManager $em, $widgetIdentifier, $identifier)
    {
        $query = $em->createQuery('DELETE FROM Neutron\\MvcBundle\\Entity\\WidgetReference w WHERE w.widgetIdentifier = ?1 AND w.widgetInstanceId = ?2');
        $query->setParameters(array(1 => $widgetIdentifier, 2 => $identifier))->execute();
    }
}