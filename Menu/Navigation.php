<?php 
namespace Neutron\MvcBundle\Menu;

use Neutron\AdminBundle\Event\ConfigureMenuEvent;

use Neutron\AdminBundle\AdminEvents;

use Knp\Menu\Matcher\Voter\UriVoter;

use Symfony\Component\HttpFoundation\Request;

use Knp\Menu\FactoryInterface;

use Symfony\Component\DependencyInjection\ContainerAware;


class Navigation extends ContainerAware
{
    const IDENTIFIER = 'mvc.navigation.main';

    public function main(FactoryInterface $factory, array $options)
    {
        $this->container->get('neutron_component.menu.voter')
            ->setUri($this->container->get('request')->getRequestUri());
      
        $pages = $this->container->get('neutron_mvc.category.manager')->buildNavigation();
  
        $menu = $factory->createFromArray($pages);
        $root = $menu->getRoot();

        $home = $factory->createItem('home', array(
            'label' => $this->container->get('translator')->trans('menu.home', array(), 'NeutronMvcBundle'),
            'route' => 'frontend_home',
        ));
        
        $root->addChild($home);
        
        $root->moveChildToPosition($home, 0);
        
        $this->container->get('event_dispatcher')
            ->dispatch(AdminEvents::onMenuConfigure, new ConfigureMenuEvent(self::IDENTIFIER, $factory, $menu));
        
        return $menu;
    }
    


}