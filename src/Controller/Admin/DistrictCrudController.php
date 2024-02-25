<?php

namespace App\Controller\Admin;

use App\Entity\District;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DistrictCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return District::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(pageName:Crud::PAGE_INDEX, title: 'Liste des districts')
            ->setEntityLabelInSingular('district')
            ->setEntityLabelInPlural('districts')
            ->setDefaultSort(['id' => 'ASC'])
            ->setSearchFields(['Nom']);
    }
     
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('Nom', 'Nom');
        yield AssociationField::new('region');  
        yield BooleanField::new('isEligibleForCrenas', 'Est éligible au CRENAS');
        yield BooleanField::new('isEligibleForCreni', 'Est éligible au CRENI');
        yield NumberField::new('Population', 'Population');
        yield NumberField::new('Superficie', 'Superficie');
        yield NumberField::new('Densite', 'Densité');
    } 

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('Nom');
    }
}
