<?php 
namespace Neutron\MvcBundle\Routing;

use Symfony\Component\Config\Loader\LoaderResolverInterface;

use Symfony\Component\Routing\Route;

use Symfony\Component\Routing\RouteCollection;

use Symfony\Component\Config\Loader\LoaderInterface;

class ExtraLoader implements LoaderInterface
{
    private $loaded = false;
    
    protected $dashboardController;
    
    protected $categoryController;
    
    protected $homeController;
    
    protected $defaultLocale;
    
    public function __construct($dashboardController, $categoryController, $homeController, $defaultLocale)
    {
        $this->dashboardController = $dashboardController;
        $this->categoryController = $categoryController;
        $this->homeController = $homeController;
        $this->defaultLocale = $defaultLocale;
    }
    
    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
           return;
        }
        
        $routes = new RouteCollection();

        $this->addDashboardRoute($routes);
        $this->addCategoryManagementRoute($routes);
        $this->addCategoryCreateRoute($routes);
        $this->addCategoryUpdateRoute($routes);
        $this->addCategoryDeleteRoute($routes);
        $this->addWidgetInstancesRoute($routes);
        
        $this->addDistributorRoute($routes);
        $this->addHomeRoute($routes);

        return $routes;
    }
    
    public function supports($resource, $type = null)
    {
        return 'extra' === $type;
    }
    
    public function getResolver()
    {}
    
    public function setResolver(LoaderResolverInterface $resolver)
    {}
    
    protected function addDashboardRoute(RouteCollection $routes)
    {
        $pattern = '/admin';
        
        $defaults = array(
            '_controller' => $this->dashboardController,
        );
        
        $route = new Route($pattern, $defaults);
        $routes->add('dashboard', $route);
    }
    
    protected function addCategoryManagementRoute(RouteCollection $routes)
    {
        $pattern = '/admin/category';
        
        $defaults = array(
            '_controller' => $this->categoryController . ':indexAction',
        );
        
        $route = new Route($pattern, $defaults);
        $routes->add('neutron_mvc.category.management', $route);
    }
    
    protected function addCategoryCreateRoute(RouteCollection $routes)
    {
        $pattern = '/admin/category/create/{parentId}';
        
        $defaults = array(
            '_controller' => $this->categoryController . ':createAction',
        );
        
        $route = new Route($pattern, $defaults);
        $routes->add('neutron_mvc.category.create', $route);
    }
    
    protected function addCategoryUpdateRoute(RouteCollection $routes)
    {
        $pattern = '/admin/category/update/{nodeId}';
        
        $defaults = array(
            '_controller' => $this->categoryController . ':updateAction',
        );
        
        $route = new Route($pattern, $defaults);
        $routes->add('neutron_mvc.category.update', $route);
    }
    
    protected function addCategoryDeleteRoute(RouteCollection $routes)
    {
        $pattern = '/admin/category/delete/{nodeId}';
        
        $defaults = array(
            '_controller' => $this->categoryController . ':deleteAction',
        );
        
        $route = new Route($pattern, $defaults);
        $routes->add('neutron_mvc.category.delete', $route);
    }
    
    protected function addHomeRoute(RouteCollection $routes)
    {
        $pattern = '/{_locale}';
        
        $defaults = array(
            '_controller' => $this->homeController,
            '_locale' => $this->defaultLocale
        );
        
        $route = new Route($pattern, $defaults);
        $routes->add('frontend_home', $route);
    }
    
    protected function addDistributorRoute(RouteCollection $routes)
    {
        $pattern = '/{slug}';
        
        $defaults = array(
            '_controller' => 'NeutronMvcBundle:Frontend\Distributor:distribute',
        );
        
        $route = new Route($pattern, $defaults);
        $routes->add('neutron_mvc.distributor', $route);
    }
    
    protected function addWidgetInstancesRoute(RouteCollection $routes)
    {
        $pattern = '/admin/_neutron_mvc/widget/instances.{_format}';
        
        $defaults = array(
            '_controller' => 'NeutronMvcBundle:Backend\Widget:instances',
            '_format' => 'json'
        );
        
        $route = new Route($pattern, $defaults);
        $routes->add('neutron_mvc.widget_intances', $route);
    }
}