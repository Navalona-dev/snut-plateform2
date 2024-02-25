<?php

namespace App\Controller\Admin;

use App\Entity\CommandeSemestrielle;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommandeSemestrielleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CommandeSemestrielle::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setPageTitle(pageName:Crud::PAGE_INDEX, title: 'Liste des date des commandes CRENI')
        ->setPageTitle(pageName:Crud::PAGE_NEW, title:"Créer un paramétrage de date de commande CRENI")
        ->setEntityLabelInSingular("commande semestrielle")
        ->setEntityLabelInPlural("commandes semestrielles")
        ->setDefaultSort(['id' => 'ASC'])
        ->setSearchFields(['Nom']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('Id', 'Id')->onlyOnIndex();
        yield AssociationField::new('AnneePrevisionnelle', 'Année prévisionnelle');
        yield TextField::new('Slug', 'Abréviation');
        yield TextField::new('Nom', 'Nom');
        yield DateField::new('DateDebut', 'Date de début');
        yield DateField::new('DateFin', 'Date fin');
        yield BooleanField::new('isActive', 'Est activé');
    }
}
