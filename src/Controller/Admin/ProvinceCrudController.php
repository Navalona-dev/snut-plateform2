<?php

namespace App\Controller\Admin;

use App\Entity\Province;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProvinceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Province::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(pageName:Crud::PAGE_INDEX, title: 'Liste des provinces')
            ->setEntityLabelInSingular('province')
            ->setEntityLabelInPlural('provinces')
            ->setDefaultSort(['id' => 'ASC'])
            ->setSearchFields(['NomFR']);
    }
    
    
    public function configureFields(string $pageName): iterable
    {
        
        return [
            IdField::new('id','Id'),
            TextField::new('NomFR', 'Nom FR'),
            TextField::new('NomMG', 'Nom MG'),
            TextField::new('ChefLieu', 'Chef-lieu'),
            TextField::new('CodeISO', 'Code ISO'),
            TextField::new('CodeFIPS', 'Code FIPS'),
            NumberField::new('Superficie', 'Superficie'),
            NumberField::new('Population', 'Population')
        ];
    }
    
}
