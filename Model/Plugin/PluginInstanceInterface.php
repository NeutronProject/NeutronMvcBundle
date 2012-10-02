<?php 
namespace Neutron\MvcBundle\Model\Plugin;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Neutron\SeoBundle\Model\SeoInterface;

interface PluginInstanceInterface
{
    public function getIdentifier();
}