<?php
namespace Neutron\MvcBundle\Model;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

interface CategoryAwareInterface
{
    public function setCategory(CategoryInterface $category);
    
    public function getCategory();
}