<?php 
namespace Neutron\MvcBundle\Model\Category;

use Neutron\TreeBundle\Model\TreeNodeInterface;

interface CategoryInterface extends TreeNodeInterface
{   
    public function setSlug($slug);
    
    public function getSlug();
    
    public function setLinkTarget($target);
    
    public function getLinkTarget();
    
    public function setEnabled($bool);
    
    public function isEnabled();
    
    public function setDisplayed($bool);
    
    public function isDisplayed();
}