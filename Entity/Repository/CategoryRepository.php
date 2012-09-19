<?php
namespace Neutron\MvcBundle\Entity\Repository;

use Symfony\Component\Security\Acl\Permission\MaskBuilder;

use Neutron\ComponentBundle\Doctrine\ORM\Query\TreeWalker\AclWalker;

use Neutron\AdminBundle\Acl\AclManagerInterface;

use Doctrine\ORM\Query;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CategoryRepository extends NestedTreeRepository
{
    public function getQueryBuilderForDataGrid()
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->select(array('c.id', 'c.title', 'c.slug', 'c.type', 'c.lvl', 'c.enabled', 'c.displayed'))
            ->where('c.type <> ?1')
            ->setParameters(array(1 => 'root'))
        ;
        
        return $qb;
    }
    
    public function findBySlugQueryBuilder()
    {
        $qb = $this->createQueryBuilder('n');
        $qb
            ->where('n.slug = :slug')
            ->andWhere('n.enabled = :enabled')
        ;
    
        return $qb;
    }
    
    public function findBySlugQuery($slug, $useCache, $locale)
    {
        $query = $this->findBySlugQueryBuilder()->getQuery();
        $query->setParameters(array('slug' => $slug, 'enabled' => true));
        
        $query->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );
        $query->setHint(
            \Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE,
            $locale
        );
        //$query->useResultCache($useCache, 7200, $this->generateCacheId($slug, $locale));
        return $query;
    }
    
    public function findBySlug($slug, $useCache, $locale)
    {
        return $this->findBySlugQuery($slug, $useCache, $locale)->getOneOrNullResult();
    }
    
    public function getCategories(AclManagerInterface $aclManager, $useTranslatable, $locale)
    {
        $qb = $this->getChildrenQueryBuilder();
        $qb->andWhere('node.enabled = :enabled AND node.displayed = :displayed');
        $qb->setParameters(array('enabled' => true, 'displayed' => true));
    
        $query = $qb->getQuery();
        
        if ($useTranslatable){
            $query->setHint(
                \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
                'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
            );
            $query->setHint(
                \Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE,
                $locale
            );
        }
        
    
        if (false === $aclManager->isAdministrativeMode()){
            $query->setHint(
                \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
                'Neutron\\ComponentBundle\\Doctrine\\ORM\\Query\\TreeWalker\\AclWalker'
            );
            $query->setHint(
                AclWalker::HINT_ACL_OPTIONS,
                array('roles' => $aclManager->getUserRoles(), 'mask' => MaskBuilder::MASK_VIEW)
            );
        }
    
        return $query->getArrayResult();
    }
    
    public function generateCacheId($slug, $locale)
    {
        return md5($this->getClassName()) . '_' . md5($slug . $locale);
    }
}