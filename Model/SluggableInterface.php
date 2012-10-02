<?php
namespace Neutron\MvcBundle\Model;

interface SluggableInterface
{
    public function setSlug($slug);
    
    public function getSlug();
}