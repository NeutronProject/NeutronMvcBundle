<?php 
namespace Neutron\MvcBundle\Model\Plugin;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Neutron\SeoBundle\Model\SeoInterface;

interface PluginInstanceInterface
{
    public function getId();
    
    public function setCategory(CategoryInterface $category);
    
    public function getCategory();
    
    public function setSeo(SeoInterface $seo);
    
    public function getSeo();
}