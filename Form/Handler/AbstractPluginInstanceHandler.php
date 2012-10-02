<?php 
namespace Neutron\MvcBundle\Form\Handler;

use Neutron\MvcBundle\Plugin\PluginInterface;

use Neutron\ComponentBundle\Form\Handler\AbstractFormHandler;

use Neutron\AdminBundle\Acl\AclManagerInterface;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use Neutron\Plugin\PageBundle\Form\Type\AbstractPluginInstanceType;

abstract class AbstractPluginInstanceHandler extends AbstractFormHandler
{      
    protected $plugin;
    
    protected $aclManager;
    
    public function setAclManager(AclManagerInterface $aclManager)
    {
        $this->aclManager = $aclManager;
        return $this;
    }

    
    public function setPlugin(PluginInterface $plugin)
    {
        $this->plugin = $plugin;
        return $this;
    }
 
    protected function onSuccess()
    {   
        $pluginInstance = $this->form->get('instance')->getData();
        $category = $pluginInstance->getCategory();

        $pluginManager = $this->plugin->getManager();
        
        $pluginManager->update($pluginInstance);
        
        if (count($this->plugin->getPanels()) > 0){
            $panels = $this->form->get('panels')->getData();
            $pluginManager->updatePanels($this->request->get('id'), $panels);
        }
        
        $acl = $this->form->get('acl')->getData();
        $this->aclManager
            ->setObjectPermissions(ObjectIdentity::fromDomainObject($category), $acl);
        
        $this->om->flush(); 
    }
}
