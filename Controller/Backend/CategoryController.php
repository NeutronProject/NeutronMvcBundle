<?php
namespace Neutron\MvcBundle\Controller\Backend;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\DependencyInjection\ContainerAware;

class CategoryController extends ContainerAware
{
    public function indexAction()
    {   
        $tree = $this->container->get('neutron.tree')
            ->get($this->container->getParameter('neutron_mvc.category.tree'));
        
        $grid = $this->container->get('neutron.datagrid')
            ->get($this->container->getParameter('neutron_mvc.category.grid'));
        
        $template = $this->container->get('templating')->render(
            'NeutronMvcBundle:Backend\Category:index.html.twig', 
            array('tree' => $tree, 'grid' => $grid)
        );
        
        return new Response($template);
    }
    
    public function createAction($parentId)
    {
        $category = $this->createCategory($parentId);
        
        $form = $this->container->get('neutron_mvc.form.category');
        $handler = $this->container->get('neutron_mvc.form.handler.category');
        
        $form->setData($category);
        
        if (null !== $handler->process()){
            return new Response(json_encode($handler->getResult()));
        }
        
        $template = $this->container->get('templating')->render(
            'NeutronMvcBundle:Backend\Category:create.html.twig', array(
                'form' => $form->createView()    
            )
        );
        
        return new Response($template);
    }
    
    public function updateAction($nodeId)
    {
        $category = $this->getCategory($nodeId);
        $pluginProvider = $this->container->get('neutron_mvc.plugin_provider');
        $updateRoute = $pluginProvider->get($category->getType())->getUpdateRoute();
        $url = $this->container->get('router')->generate($updateRoute, array('id' => $category->getId()));
        return new RedirectResponse($url);
    }
    
    public function deleteAction($nodeId)
    {
        $category = $this->getCategory($nodeId);
        $pluginProvider = $this->container->get('neutron_mvc.plugin_provider');
        $deleteRoute = $pluginProvider->get($category->getType())->getDeleteRoute();
        $redirectUrl = $this->container->get('router')
            ->generate($deleteRoute, array('id' => $category->getId()));
        return new RedirectResponse($redirectUrl);
    }

    protected function getTreeManager()
    {
        $manager = $this->container->get('neutron_tree.manager.factory')
            ->getManagerForClass($this->container->getParameter('neutron_mvc.category.category_class'));
        return $manager;
    }
    
    protected function createCategory($parentId)
    {     
        $treeManager = $this->getTreeManager();
        $node = $treeManager->createNode();
        $parent = $treeManager->findNodeBy(array('id' => (int) $parentId));
        
        if (!$parent){
            throw new NotFoundHttpException();
        }
        
        $node->setParent($parent);
        
        return $node;
    }
    
    public function getCategory($id)
    {
        $treeManager = $this->getTreeManager();
       
        $category = $treeManager->findNodeBy(array('id' => $id));
        
        if (!$category){
            throw new NotFoundHttpException();
        }
        
        return $category;
    }
}
