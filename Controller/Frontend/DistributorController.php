<?php
namespace Neutron\MvcBundle\Controller\Frontend;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Neutron\UserBundle\Model\BackendRoles;

use Neutron\AdminBundle\Entity\MainTree;

use Symfony\Component\Security\Acl\Permission\MaskBuilder;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Neutron\AdminBundle\Acl\AclManager;

use Neutron\MvcBundle\Provider\PluginProvider;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;

class DistributorController extends Controller
{
    public function distributeAction($slug)
    {  
        $categoryManager = $this->container->get('neutron_mvc.category.manager');
        
        $pluginProvider = $this->container->get('neutron_mvc.plugin_provider');
        $category = $categoryManager
            ->findCategoryBySlug($slug, true, $this->container->get('request')->getLocale());
        
        if (null === $category){
            throw new NotFoundHttpException();
        }
        
        if (false === $this->container->get('neutron_admin.acl.manager')->isGranted($category, 'VIEW')){
            throw new AccessDeniedException();
        }
        
        $plugin = $pluginProvider->get($category->getType());
        $controller = $plugin->getFrontController();
        
        $httpKernel = $this->container->get('http_kernel');
        $response = $httpKernel->forward($controller, array(
            'category' => $category,
        ));
        
        return $response;
    }
}