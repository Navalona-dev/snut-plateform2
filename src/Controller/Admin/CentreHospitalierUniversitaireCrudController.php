<?php

namespace App\Controller\Admin;

use App\Entity\CentreHospitalierUniversitaire;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CentreHospitalierUniversitaireCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CentreHospitalierUniversitaire::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setPageTitle(pageName:Crud::PAGE_INDEX, title: 'Liste des Centres Hospitalier Universitaire')
        ->setPageTitle(pageName:Crud::PAGE_NEW, title:"Créer un Centre Hospitalier Universitaire")
        ->setEntityLabelInSingular('Centre Hospitalier Universitaire')
        ->setEntityLabelInPlural('Centres Hospitalier Universitaire')
        ->setDefaultSort(['id' => 'ASC'])
        ->setSearchFields(['Nom']);
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('Id', 'Id')->onlyOnIndex(); 
        yield AssociationField::new('District'); 
        yield TextField::new('Nom', 'Nom');
    }
}
