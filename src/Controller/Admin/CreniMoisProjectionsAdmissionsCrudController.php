<?php

namespace App\Controller\Admin;

use App\Entity\CreniMoisProjectionsAdmissions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CreniMoisProjectionsAdmissionsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CreniMoisProjectionsAdmissions::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setPageTitle(pageName:Crud::PAGE_INDEX, title: 'Liste des mois de projections et d\'admission par semestre')
        ->setPageTitle(pageName:Crud::PAGE_NEW, title:"Créer le mois de projection et d'admission") 
        ->setDefaultSort(['id' => 'ASC'])
        ->setSearchFields(['MoisProjectionAnneePrevisionnelle']);
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('Id', 'Id')->onlyOnIndex(); 
        yield AssociationField::new('CommandeSemestrielle', 'Commande');
        yield TextField::new('Mois01AdmissionCreniPrecedent', 'Mois 01 d\'admission CRENAS enregistré');
        yield TextField::new('Mois02AdmissionCreniPrecedent', 'Mois 02 d\'admission CRENAS enregistré');
        yield TextField::new('Mois03AdmissionCreniPrecedent', 'Mois 03 d\'admission CRENAS enregistré');
        yield TextField::new('Mois04AdmissionCreniPrecedent', 'Mois 04 d\'admission CRENAS enregistré');
        yield TextField::new('Mois05AdmissionCreniPrecedent', 'Mois 05 d\'admission CRENAS enregistré');
        yield TextField::new('Mois06AdmissionCreniPrecedent', 'Mois 06 d\'admission CRENAS enregistré');
        yield TextField::new('Mois01AdmissionProjeteAnneePrecedent', 'Mois 01 d\'admission qui avait été projeté');
        yield TextField::new('Mois02AdmissionProjeteAnneePrecedent', 'Mois 02 d\'admission qui avait été projeté');
        yield TextField::new('Mois03AdmissionProjeteAnneePrecedent', 'Mois 03 d\'admission qui avait été projeté');
        yield TextField::new('Mois04AdmissionProjeteAnneePrecedent', 'Mois 04 d\'admission qui avait été projeté');
        yield TextField::new('Mois05AdmissionProjeteAnneePrecedent', 'Mois 05 d\'admission qui avait été projeté');
        yield TextField::new('Mois06AdmissionProjeteAnneePrecedent', 'Mois 06 d\'admission qui avait été projeté');
        yield TextField::new('Mois01ProjectionAnneePrevisionnelle', 'Mois 01 d\'admission prévisionnelle');        
        yield TextField::new('Mois02ProjectionAnneePrevisionnelle', 'Mois 02 d\'admission prévisionnelle');        
        yield TextField::new('Mois03ProjectionAnneePrevisionnelle', 'Mois 03 d\'admission prévisionnelle');        
        yield TextField::new('Mois04ProjectionAnneePrevisionnelle', 'Mois 04 d\'admission prévisionnelle');        
        yield TextField::new('Mois05ProjectionAnneePrevisionnelle', 'Mois 05 d\'admission prévisionnelle');        
        yield TextField::new('Mois06ProjectionAnneePrevisionnelle', 'Mois 06 d\'admission prévisionnelle');        
    }
}
