<?php 
namespace Neutron\MvcBundle\EventListener;

use Neutron\MvcBundle\DataGrid\Category;

use Neutron\Bundle\DataGridBundle\Event\DataEvent;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;

class CategoryListener
{
    
    protected $translator;
    
    protected $translationDomain;
    
    public function __construct(Translator $translator, $translationDomain)
    {
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;
    }
    
    public function onDataResponse(DataEvent $event)
    {
        $name = $event->getName();
        $data = $event->getData();
     
        if ($name == Category::IDENTIFIER){
            $data = $this->translateData($data);
            $event->setData($data);
        }
    }
    
    private function translateData(array $data)
    {   
    
        $translatedData = array();
        foreach ($data['rows'] as $idx => $row){
            $translatedData['rows'][$idx]['cell'][2] = 
                $this->translator->trans($data['rows'][$idx]['cell'][2], array(), $this->translationDomain);
        }
  
        return array_replace_recursive($data, $translatedData);
    }
}