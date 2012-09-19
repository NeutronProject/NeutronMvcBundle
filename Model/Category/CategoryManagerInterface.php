<?php
namespace Neutron\MvcBundle\Model\Category;

interface CategoryManagerInterface
{
    public function getQueryBuilderForDataGrid();
    
    public function findCategoryBySlug($slug, $useCache);
}