<?php
namespace Neutron\MvcBundle\Model\Category;

interface CategoryManagerInterface
{
    public function findOneBy(array $criteria);
    
    public function findBy(array $criteria);
    
    public function getQueryBuilderForDataGrid();
    
    public function findCategoryBySlug($slug, $useCache);
}