<?php
namespace Neutron\MvcBundle\Entity\Repository;

use \Doctrine\ORM\EntityRepository;

class WidgetReferenceRepository extends EntityRepository
{
    public function getWidgetReferencesByPanel($pluginInstanceId, $pluginIdentifier, $strategyPanelName)
    {

        $dql = 'SELECT w FROM Neutron\\MvcBundle\\Entity\\WidgetReference w 
                WHERE w.strategyPanelName = ?1 
                    AND w.pluginInstanceId = ?2
                    AND w.pluginIdentifier = ?3
                ORDER BY w.position ASC   
        ';
        
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameters(array(
            1 => $strategyPanelName,
            2 => $pluginInstanceId,
            3 => $pluginIdentifier
        ));
        
        return $query->getResult();
    }
    
}


