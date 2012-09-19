<?php
namespace Neutron\MvcBundle\Doctrine\EventListener;

use Neutron\AdminBundle\Helper\ApplicationHelper;

use Doctrine\ORM\Query;

use Neutron\MvcBundle\DataGrid\Category;

use Neutron\Bundle\DataGridBundle\Event\Doctrine\ORM\QueryEvent;

class CategoryDataGridListener
{
    protected $applicationHelper;
    
    protected $useTranslatable;
    
    public function __construct(ApplicationHelper $applicationHelper, $useTranslatable)
    {
        $this->applicationHelper = $applicationHelper;
        $this->useTranslatable = $useTranslatable;
    }
    
    public function onQueryReady(QueryEvent $event)
    {
        if ($event->getName() !== Category::IDENTIFIER || $this->useTranslatable === false){
            return;
        }
        
        $query = $event->getQuery();
        
        $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 
                'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker');
        
        $query->setHint(\Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE, 
                $this->applicationHelper->getFrontLocale());
    }
}