<?php 
namespace Neutron\MvcBundle\Doctrine;

use Neutron\AdminBundle\Acl\AclManagerInterface;

use Doctrine\Common\Persistence\ObjectManager;

use Neutron\MvcBundle\Model\Category\CategoryManagerInterface;

use Symfony\Component\Security\Acl\Permission\MaskBuilder;

use Neutron\ComponentBundle\Doctrine\ORM\Query\TreeWalker\AclWalker;

use Symfony\Component\HttpFoundation\Request;

use Neutron\MvcBundle\Provider\PluginProviderInterface;

use Neutron\TreeBundle\Model\TreeManagerFactoryInterface;

class CategoryManager implements CategoryManagerInterface 
{
    
    protected $om;
    
    protected $repository;
    
    protected $cache;
    
    protected $router;
    
    protected $request;
    
    protected $aclManager;
    
    protected $className;
    
    protected $translatable;
    
    public function __construct(ObjectManager $om, Request $request,
             AclManagerInterface $aclManager, $className, $translatable)
    {
        $this->om = $om;
        $this->repository = $om->getRepository($className);
        $this->cache = $om->getConfiguration()->getResultCacheImpl();
        $this->request = $request;
        $this->aclManager = $aclManager;
        $this->className = $className;
        $this->translatable = $translatable;
    }
    
    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }
    
    public function findBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }
    
    public function getQueryBuilderForDataGrid()
    {
        return $this->repository->getQueryBuilderForDataGrid();
    }
    
    public function findCategoryBySlug($slug, $useCache)
    {
        return $this->repository->findBySlug($slug, $useCache, $this->request->getLocale());
    }
    
    public function buildNavigation()
    {
        $root = $this->repository->getRoot();
        $categories = $this->getCategories();
        array_unshift($categories, $root);
        //var_dump($categories); die;
        $nestedTree = array();
        $l = 0;
    
        if (count($categories) > 0) {
            $stack = array();
            foreach ($categories as $category) {
                $item = array(
                    'name' => $category['type'] . $category['id'],
                    'label' => $category['title'],
                    'route' => 'neutron_mvc.distributor',
                    'routeParameters' => array('slug' => $category['slug']),
                    'display' => $category['displayed'],                    
                    'lvl' => $category['lvl'],
                    'children' => array()   
                );
          
                // Number of stack items
                $l = count($stack);
                // Check if we're dealing with different levels
                while($l > 0 && $stack[$l - 1]['lvl'] >= $item['lvl']) {
                    array_pop($stack);
                    $l--;
                }
                // Stack is empty (we are inspecting the root)
                if ($l == 0) {
                    // Assigning the root child
                    $i = count($nestedTree);
            
                    $nestedTree[$i] = $item;
                    $stack[] = &$nestedTree[$i];
                } else {
                    // Add child to parent
                    $i = count($stack[$l - 1]['children']);
                    $stack[$l - 1]['children'][$i] = $item;
                    $stack[] = &$stack[$l - 1]['children'][$i];
                }
            }
        }
    
        if (count($nestedTree) > 0){
            return $nestedTree[0];
        }
        
        return $nestedTree;
    }
    
    public function getCategories()
    {   
        return $this->repository
            ->getCategories($this->aclManager, $this->translatable, $this->request->getLocale());
    }
}