<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(pageName:Crud::PAGE_INDEX, title: 'Liste des produits')
            ->setEntityLabelInSingular('produit')
            ->setEntityLabelInPlural('produits')
            ->setDefaultSort(['Nom' => 'ASC'])
            ->setPaginatorPageSize(100);
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('Type');
        yield TextField::new('Nom', 'Nom');
    } 
}
