<?php 
namespace Neutron\MvcBundle\Doctrine\EventSubscriber;

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
    
    protected $scheduledForDeletionWidgetReferences = array();
    
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        
        foreach ($uow->getScheduledEntityDeletions() as $entity) {
        
            if ($entity instanceof WidgetInstanceInterface){
                $this->scheduledForDeletionWidgetReferences[] = $entity->getIdentifier();
            }
        }
    }
    
    public function postFlush(PostFlushEventArgs $args)
    {
        if (count($this->scheduledForDeletionWidgetReferences) > 0){
            $em = $args->getEntityManager(); 
            
            foreach ($this->scheduledForDeletionWidgetReferences as $identifier){
                $this->deleteWidgetReferencesByIdentifier($em, $identifier);
            }          
        }     
    }
    
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        
        if ($entity instanceof WidgetReferenceInterface){
            $widget = $this->container->get('neutron_mvc.widget_provider')
                        ->get($entity->getStrategyWidgetName());
            
            $entity->setWidget($widget);
        }
    }
    
    public function getSubscribedEvents()
    {
        return array(Events::onFlush, Events::postFlush, Events::postLoad);
    }
    
    protected function deleteWidgetReferencesByIdentifier(EntityManager $em, $identifier)
    {
        $query = $em->createQuery('DELETE FROM Neutron\\MvcBundle\\Entity\\WidgetReference w WHERE w.identifier = ?1');
        $query->setParameter(1, $identifier)->execute();
    }
}