<?php
namespace Neutron\MvcBundle\Controller\Backend;

use Neutron\MvcBundle\Plugin\PluginInterface;

use Symfony\Component\Form\FormInterface;

use Neutron\MvcBundle\Form\Handler\AbstractPluginInstanceHandler;

use Neutron\MvcBundle\Form\Type\AbstractPluginInstanceType;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use Symfony\Component\DependencyInjection\ContainerAware;

use Neutron\MvcBundle\Model\Plugin\PluginInstanceInterface;

use Neutron\SeoBundle\Model\SeoInterface;

use Neutron\TreeBundle\Model\TreeNodeInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpFoundation\Response;

abstract class AbstractPluginInstanceController extends ContainerAware
{   
    protected $form;
    
    protected $formHandler;
    
    protected $plugin;
    
    public function setForm(FormInterface $form)
    {
        $this->form = $form;
        return $this;
    }
    
    public function setFormHandler(AbstractPluginInstanceHandler $formHandler)
    {
        $this->formHandler = $formHandler;
        return $this;
    }

    public function setPlugin(PluginInterface $plugin)
    {
        $this->plugin = $plugin;
        return $this;
    }
    
    public function updateAction($id)
    {   
        $this->form->setData($this->getData($id));
        
        if (null !== $this->formHandler->process()){
            return new Response(json_encode($this->formHandler->getResult()));
        }

        $template = $this->container->get('templating')
            ->render('NeutronMvcBundle:Backend\PluginInstance:update.html.twig', array(
                'form' => $this->form->createView(),
                'plugin' => $this->plugin
            )
        );
    
        return  new Response($template);
    }
    
    public function deleteAction($id)
    {
        $category = $this->getCategory($id);
        $pluginInstance = $this->getPluginInstance($category);
    
        if ($this->container->get('request')->getMethod() == 'POST'){
            $this->doDelete($category, $pluginInstance);
            $redirectUrl = $this->container->get('router')->generate('neutron_mvc.category.management');
            return new RedirectResponse($redirectUrl);
        }
    
        $template = $this->container->get('templating')
            ->render('NeutronMvcBundle:Backend\PluginInstance:delete.html.twig', array(
                'record' => $pluginInstance,
                'plugin' => $this->plugin
            )
        );
    
        return  new Response($template); 
    }
    
    protected function doDelete(TreeNodeInterface $category, PluginInstanceInterface $pluginInstance)
    {
        $this->container->get('neutron_admin.acl.manager')
            ->deleteObjectPermissions(ObjectIdentity::fromDomainObject($pluginInstance->getCategory()));
        
        $this->plugin->getManager()->delete($pluginInstance, true);
    }
    
    protected function getCategory($id)
    {
        $treeManager = $this->container->get('neutron_tree.manager.factory')
            ->getManagerForClass($this->container->getParameter('neutron_mvc.category.category_class'));
    
        $category = $treeManager->findNodeBy(array('id' => $id));
    
        if (!$category){
            throw new NotFoundHttpException();
        }
    
        return $category;
    }
    
    protected function getPluginInstance(TreeNodeInterface $category)
    {
        $pluginInstance = $this->plugin->getManager()->findOneBy(array('category' => $category));
    
        if (!$pluginInstance){
            throw new NotFoundHttpException();
        }
    
        return $pluginInstance;
    }
    
    
    protected function getSeo(PluginInstanceInterface $pluginInstance)
    {
    
        if(!$pluginInstance->getSeo() instanceof SeoInterface){
            $manager = $this->container->get('neutron_seo.manager');
            $seo = $manager->createSeo();
            $pluginInstance->setSeo($seo);
        }
    
        return $pluginInstance->getSeo();
    }
    
    protected function getData($id)
    {
        $category = $this->getCategory($id);
        $pluginInstance = $this->getPluginInstance($category);
        $seo = $this->getSeo($pluginInstance);
        $panels = $this->plugin->getManager()->getPanelsForUpdate($id, $this->plugin->getName());
    
        return array(
            'general' => $category,
            'instance' => $pluginInstance,
            'seo'     => $seo,
            'panels'  => $panels,
            'acl' => $this->container->get('neutron_admin.acl.manager')
                ->getPermissions(ObjectIdentity::fromDomainObject($category))
        );
    }
}