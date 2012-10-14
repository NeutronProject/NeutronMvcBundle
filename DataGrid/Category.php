<?php
namespace Neutron\MvcBundle\DataGrid;

use Neutron\MvcBundle\Model\Category\CategoryManagerInterface;

use Symfony\Component\Translation\TranslatorInterface;

use Doctrine\ORM\Query;

use Neutron\AdminBundle\Helper\ApplicationHelper;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Neutron\Bundle\DataGridBundle\DataGrid\FactoryInterface;

class Category
{

    const IDENTIFIER = 'category';
    
    protected $factory;
    
    protected $categoryManager;
    
    protected $translator;
    
    protected $router;

    public function __construct (FactoryInterface $factory, CategoryManagerInterface $categoryManager, 
            TranslatorInterface $translator, Router $router)
    {
        $this->factory = $factory;
        $this->categoryManager = $categoryManager;
        $this->translator = $translator;
        $this->router = $router;
    }

    public function build ()
    {
        $dataGrid = $this->factory->createDataGrid(self::IDENTIFIER);
        $dataGrid
            ->setCaption(
                $this->translator->trans('grid.category.title',  array(), 'NeutronMvcBundle')
            )
            ->setAutoWidth(true)
            ->setColNames(array(
                $this->translator->trans('grid.category.column.title',  array(), 'NeutronMvcBundle'),
                $this->translator->trans('grid.category.column.slug',  array(), 'NeutronMvcBundle'),
                $this->translator->trans('grid.category.column.type',  array(), 'NeutronMvcBundle'),
                $this->translator->trans('grid.category.column.level',  array(), 'NeutronMvcBundle'),
                $this->translator->trans('grid.category.column.enabled',  array(), 'NeutronMvcBundle'),
                $this->translator->trans('grid.category.column.displayed',  array(), 'NeutronMvcBundle'),
            ))
            ->setColModel(array(
                array(
                    'name' => 'c.title', 'index' => 'c.title', 'width' => 200, 
                    'align' => 'left', 'sortable' => true, 'search' => true,
                ), 
                    
                array(
                    'name' => 'c.slug', 'index' => 'c.slug',  'width' => 200, 
                    'align' => 'left',  'sortable' => true, 'search' => true,
                ), 
                    
                array(
                    'name' => 'c.type', 'index' => 'c.type', 'width' => 200, 
                    'align' => 'left', 'sortable' => true, 'search' => true,
                ), 
                    
                array(
                    'name' => 'c.lvl', 'index' => 'c.lvl', 'width' => 200, 
                    'align' => 'left', 'sortable' => true, 'search' => true,
                ), 
                        
                array(
                    'name' => 'c.enabled', 'index' => 'c.enabled', 'width' => 40, 
                    'align' => 'left', 'sortable' => true, 'formatter' => 'checkbox', 
                    'search' => true, 'stype' => 'select',
                    'searchoptions' => array('value' => array(
                        1 => $this->translator->trans('category.enabled'), 
                        0 => $this->translator->trans('category.disabled')
                    ))
                ),
                array(
                    'name' => 'c.displayed', 'index' => 'c.displayed', 'width' => 40, 
                    'align' => 'left', 'sortable' => true, 'formatter' => 'checkbox', 
                    'search' => true, 'stype' => 'select',
                    'searchoptions' => array('value' => array(
                        1 => $this->translator->trans('category.enabled'), 
                        0 => $this->translator->trans('category.disabled')
                    ))
                ),
     
            ))
            ->setHideGrid(true)
            ->setQueryBuilder($this->categoryManager->getQueryBuilderForDataGrid())
            ->setSortName('c.lvl')
            ->setSortOrder('asc')
            ->enablePager(true)
            ->enableViewRecords(true)
            ->enableSearchButton(true)
            ->enableEditButton(true)
            ->setEditBtnUri($this->router->generate('neutron_mvc.category.update', array('nodeId' => '{id}'), true))
            ->enableDeleteButton(true)
            ->setDeleteBtnUri($this->router->generate('neutron_mvc.category.delete', array('nodeId' => '{id}'), true))
            ->setTreeName('category')
        ;

        return $dataGrid;
    }

}