<?php

namespace App\Controller\Admin;

use App\Entity\MoisProjectionsAdmissions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MoisProjectionsAdmissionsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MoisProjectionsAdmissions::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setPageTitle(pageName:Crud::PAGE_INDEX, title: 'Liste des mois de projections et d\'admission par groupe')
        ->setPageTitle(pageName:Crud::PAGE_NEW, title:"Créer le mois de projection et d'admission") 
        ->setDefaultSort(['id' => 'ASC'])
        ->setSearchFields(['MoisProjectionAnneePrevisionnelle']);
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('Id', 'Id')->onlyOnIndex();
        yield AssociationField::new('Groupe', 'Groupe');
        yield AssociationField::new('CommandeTrimestrielle', 'Commande');
        yield TextField::new('MoisAdmissionCRENASAnneePrecedent', 'Mois d\'admission CRENAS enregistré');
        yield TextField::new('MoisAdmissionProjeteAnneePrecedent', 'Mois d\'admission qui avait été projeté');
        yield TextField::new('MoisProjectionAnneePrevisionnelle', 'Mois d\'admission prévisionnelle');        
    }
    
}
