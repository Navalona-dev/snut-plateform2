<?php

namespace App\Controller\Admin;

use App\Entity\CentreRecuperation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CentreRecuperationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CentreRecuperation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setPageTitle(pageName:Crud::PAGE_INDEX, title: 'Liste des centres de récuperation')
        ->setPageTitle(pageName:Crud::PAGE_NEW, title:"Créer un centre de récuperation")
        ->setEntityLabelInSingular('Centrerecuperation')
        ->setEntityLabelInPlural('Centrerecuperations')
        ->setDefaultSort(['id' => 'ASC'])
        ->setSearchFields(['Sigle']);
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('Id', 'Id')->onlyOnIndex(); 
        yield TextField::new('Sigle', 'Sigle');
        yield TextField::new('Nom', 'Nom');
    }
    
}
