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
 *         @ORM\Index( name="widget_reference_idx", columns={"plugin_instance_id", "plugin_identifier", "panel", "widget_instance_id", "widget_identifier"})
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
     * @var integer
     *
     * @Assert\Type(type="integer")
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", name="plugin_instance_id", length=10, nullable=false, unique=false)
     */
    protected $pluginInstanceId;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", name="plugin_identifier", length=150, nullable=false, unique=false)
     * @Assert\NotBlank()
     */
    protected $pluginIdentifier;
    
    /**
     * @var integer
     * 
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", name="widget_instance_id", length=10, nullable=false, unique=false)
     */
    protected $widgetInstanceId;
        
    /**
     * @var string 
     *
     * @ORM\Column(type="string", name="widget_identifier", length=150, nullable=false, unique=false)
     * @Assert\NotBlank()
     */
    protected $widgetIdentifier;
    

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="string", name="panel", length=50, nullable=false, unique=false)
     */
    protected $strategyPanelName;
    
    /**
     * @var integer
     * 
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     * @ORM\Column(name="position", type="integer")
     */
    protected $position;

    /**
     * @var WidgetInterface
     */
    protected $widget;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setPluginInstanceId($pluginInstanceId) 
    {
        $this->pluginInstanceId = (int) $pluginInstanceId;   
        return $this;
    }
    
    public function getPluginInstanceId()
    {
        return $this->pluginInstanceId;
    }
    
    public function setPluginIdentifier($pluginIdentifier)
    {
        $this->pluginIdentifier = (string) $pluginIdentifier;
        return $this;
    }
    
    public function getPluginIdentifier()
    {
        return $this->pluginIdentifier;
    }
    
    public function setWidgetIdentifier($widgetIdentifier)
    {
        $this->widgetIdentifier = (string) $widgetIdentifier;
        return $this;
    }
    
    public function getWidgetIdentifier()
    {
        return $this->widgetIdentifier;
    }
    
    public function setWidgetInstanceId($widgetInstanceId)
    {
        $this->widgetInstanceId = (int) $widgetInstanceId;
        return $this;
    }
    
    public function getWidgetInstanceId()
    {
        return $this->widgetInstanceId;
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
        return $this->getWidget()->getManager()->get($this->widgetInstanceId);
    }
    
    public function getLabel()
    {
        return $this->getWidgetInstance()->getLabel();
    }
}