<?php
namespace Neutron\MvcBundle\Command;

use Neutron\TreeBundle\Model\TreeNodeInterface;

use Neutron\TreeBundle\Model\TreeManagerInterface;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Console\Input\InputOption;

use Symfony\Component\Console\Input\InputArgument;

use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Input\InputInterface;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class CategoryCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('neutron:mvc:category')
            ->setDescription('Creates root node of the category tree.')
            ->setDefinition(array(
                new InputArgument('root_title', InputArgument::REQUIRED, 'The root title'),
                new InputOption('recreate', null, InputOption::VALUE_NONE, 'Deletes all tree nodes and create new root node'),
            ))
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $title = trim($input->getArgument('root_title'));
        $recreate = (true === $input->getOption('recreate'));
        $request = $this->getContainer()->set('request', new Request());
        $this->getContainer()->enterScope('request');
        
        $manager = $this->getContainer()->get('neutron_tree.factory')
            ->createManager($this->getContainer()->getParameter('neutron_mvc.category.category_class'));
        
        $root = $manager->getRoot();
        
        if ($root){
            $this->updateRoot($root, $manager, $title, $output, $recreate);
        } else {
            $this->createRoot($manager, $title, $output);
        }
        
    }
    
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('root_title')) {
            $title = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a root title:',
                function($title)
                {
                    if (empty($title)) {
                        throw new \Exception('Root title can not be empty');
                    }
    
                    return $title;
                }
            );

            $input->setArgument('root_title', $title);
        }
    }
    
    protected function createRoot(TreeManagerInterface $manager, $title, OutputInterface $output)
    {
        $root = $manager->createNode();
        $root->setTitle($title);
        $root->setSlug($title);
        $root->setType('root');
        $root->setDisplayed(true);
        $root->setEnabled(true);
        
        $manager->persistNode($root);
        
        $output->writeln('Root node has beed created.');
    }
    
    protected function updateRoot(TreeNodeInterface $root, TreeManagerInterface $manager, 
            $title, OutputInterface $output, $recreate = false)
    {
        if ($recreate){
            $manager->deleteNode($root);
            $root = $manager->createNode();
            $root->setTitle($title);
            $root->setSlug($title);
            $root->setType('root');
            $root->setDisplayed(true);
            $root->setEnabled(true);
            $manager->persistNode($root);
            $output->writeln('Root node has beed recreated.');
        } else {
            $root->setTitle($title);
            $root->setSlug($title);
            $root->setType('root');
            $root->setDisplayed(true);
            $root->setEnabled(true);
            $manager->updateNode($root);
            
            $output->writeln('Root node has beed updated.');
        }
        
        
    }
}
