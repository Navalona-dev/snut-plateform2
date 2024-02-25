<?php

namespace App\Controller\Admin;

use App\Entity\Region;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RegionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Region::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(pageName:Crud::PAGE_INDEX, title: 'Liste des régions')
            ->setEntityLabelInSingular('region')
            ->setEntityLabelInPlural('regions')
            ->setDefaultSort(['id' => 'ASC'])
            ->setSearchFields(['Nom']);
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('Nom', 'Nom');
        yield AssociationField::new('province');
        yield TextField::new('ChefLieu', 'Chef-lieu');
        yield NumberField::new('Population', 'Population');
        yield NumberField::new('Superficie', 'Superficie');
        yield NumberField::new('Densite', 'Densité');
    }
    
}
