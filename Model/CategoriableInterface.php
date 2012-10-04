<?php
namespace Neutron\MvcBundle\Model;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

interface CategoriableInterface
{
    public function setCategory(CategoryInterface $category);
    
    public function getCategory();
}