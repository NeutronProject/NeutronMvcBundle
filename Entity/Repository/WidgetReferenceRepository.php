<?php
namespace Neutron\MvcBundle\Entity\Repository;

use \Doctrine\ORM\EntityRepository;

class WidgetReferenceRepository extends EntityRepository
{
    public function getWidgetReferencesByPanel($category, $strategyPluginName, $strategyPanelName)
    {

        $dql = 'SELECT w FROM Neutron\\MvcBundle\\Entity\\WidgetReference w 
                INDEX BY w.identifier
                WHERE w.strategyPanelName = ?1 AND w.strategyPluginName = ?2 
                    AND w.category = ?3
                ORDER BY w.position ASC   
        ';
        
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameters(array(
            1 => $strategyPanelName,
            2 => $strategyPluginName,
            3 => $category
        ));
        
        return $query->getResult();
    }
    
}


