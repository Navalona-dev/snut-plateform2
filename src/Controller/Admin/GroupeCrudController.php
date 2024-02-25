<?php

namespace App\Controller\Admin;

use App\Entity\Groupe;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GroupeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Groupe::class;
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setPageTitle(pageName:Crud::PAGE_INDEX, title: 'Liste des groupes par année')
        ->setPageTitle(pageName:Crud::PAGE_NEW, title:"Créer un nouveau groupe pour l'année")
        ->setEntityLabelInSingular('groupe')
        ->setEntityLabelInPlural('groupes')
        ->setDefaultSort(['id' => 'ASC'])
        ->setSearchFields(['Nom']);
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('Id', 'Id')->onlyOnIndex();
        yield AssociationField::new('Annee', 'Année');
        yield TextField::new('Nom', 'Nom du Groupe');
        yield AssociationField::new('Provinces', 'Liste des provinces') 
        ->formatValue(function ($value, $entity) { 
            $provinceNames = []; 
            foreach ($entity->getProvinces() as $province) {
                $provinceNames[] = $province->getNomFR();
            }
            return implode(', ', $provinceNames);
        });
        
    }
    
}
