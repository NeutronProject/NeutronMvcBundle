<?php 
/*
 * This file is part of NeutronMvcBundle
 *
 * (c) Zender <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\MvcBundle\Entity;

use Neutron\MvcBundle\Widget\WidgetInterface;

use Neutron\MvcBundle\Model\Widget\WidgetReferenceInterface;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Doctrine\ORM\Mapping as ORM;

/**
 * 
 * @ORM\Table(name="neutron_widget_reference", indexes={
 *         @ORM\Index( name="widget_reference_idx", columns={"widget", "plugin", "panel", "widget_identifier", "category"})
 *     })
 * )
 * @ORM\Entity(repositoryClass="Neutron\MvcBundle\Entity\Repository\WidgetReferenceRepository")
 * @UniqueEntity("identifier")
 */
class WidgetReference implements WidgetReferenceInterface
{
    /**
     * @var integer 
     *
     * @ORM\Id @ORM\Column(name="id", type="integer")
     * 
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
        
    /**
     * @var string 
     *
     * @ORM\Column(type="string", name="widget_identifier", length=50, nullable=false, unique=false)
     * @Assert\NotBlank()
     */
    protected $identifier;
    
    /**
     * @var integer
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     * @ORM\Column(type="string", name="category", length=50, nullable=false, unique=false)
     */
    protected $category;
    
    /**
     * @var integer
     * 
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     * @ORM\Column(name="position", type="integer")
     */
    protected $position;
    
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="string", name="plugin", length=50, nullable=false, unique=false)
     */
    protected $strategyPluginName;
    

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="string", name="widget", length=50, nullable=false, unique=false)
     */
    protected $strategyWidgetName;
    
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="string", name="panel", length=50, nullable=false, unique=false)
     */
    protected $strategyPanelName;
    
    /**
     * @var WidgetInterface
     */
    protected $widget;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setIdentifier($identifier)
    {
        $this->identifier = (string) $identifier;     
        return $this;
    }
    
    public function getIdentifier()
    {
        return $this->identifier;
    }
    
    public function setCategory($category)
    {
        $this->category = (string) $category;
        return $this;
    }
    
    public function getCategory()
    {
        return $this->category;
    }
    
    public function setStrategyWidgetName($name)
    {
        $this->strategyWidgetName = (string) $name;
        return $this;
    }
    
    public function getStrategyWidgetName()
    {
        return $this->strategyWidgetName;
    }
    
    public function setStrategyPluginName($name)
    {
        $this->strategyPluginName = (string) $name;
        return $this;
    }
    
    public function getStrategyPluginName()
    {
        return $this->strategyPluginName;
    }
    
    public function setStrategyPanelName($name)
    {
        $this->strategyPanelName = (string) $name;
        return $this;
    }
    
    public function getStrategyPanelName()
    {
        return $this->strategyPanelName;
    }
    
    public function setPosition($position)
    {
        $this->position = (int) $position;
    }
    
    public function getPosition()
    {
        return $this->position;
    }
    
    public function setWidget(WidgetInterface $widget)
    {
        $this->widget = $widget;
    }
    
    public function getWidget()
    {
        if (null === $this->widget){
            throw new \RuntimeException('Widget is not set.');
        }
        
        return $this->widget;
    }
        
    public function getWidgetInstance()
    {
        return $this->getWidget()->getManager()->get($this->identifier);
    }
    
    public function getLabel()
    {
        return $this->getWidgetInstance()->getLabel();
    }
}