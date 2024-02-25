<?php

namespace App\Controller\Admin;

use App\Entity\AnneePrevisionnelle;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class AnneePrevisionnelleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AnneePrevisionnelle::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setPageTitle(pageName:Crud::PAGE_INDEX, title: 'Liste des années prévisionnelle')
        ->setPageTitle(pageName:Crud::PAGE_NEW, title:"Créer une année prévisionnelle")
        ->setEntityLabelInSingular('Anneeprevisionnelle')
        ->setEntityLabelInPlural('Anneeprevisionnelles')
        ->setDefaultSort(['id' => 'ASC'])
        ->setSearchFields(['Annee']);
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('Id', 'Id')->onlyOnIndex();
        yield NumberField::new('Annee', 'Année');
        yield NumberField::new('ValeurCalculTheoriqueATPE01', 'Valeur de calcul pour ATPE 1er collecte');
        yield NumberField::new('ValeurCalculTheoriqueAMOX01', 'Valeur de calcul pour AMOX 1er collecte');
        yield NumberField::new('ValeurCalculTheoriqueFichePatient01', 'Valeur de calcul pour Fiche Patient 1er collecte');
        yield NumberField::new('ValeurCalculTheoriqueRegistre01', 'Valeur de calcul pour le registre 1er collecte');
        yield NumberField::new('ValeurCalculTheoriqueCarnetRapport01', 'Valeur de calcul pour le carnet de rapport mensuel 1er collecte');
        yield NumberField::new('ValeurCalculTheoriqueATPE02', 'Valeur de calcul pour ATPE autres collectes');
        yield NumberField::new('ValeurCalculTheoriqueAMOX02', 'Valeur de calcul pour AMOX autres collectes');
        yield NumberField::new('ValeurCalculTheoriqueFichePatient02', 'Valeur de calcul pour Fiche Patient autres collectes');
        yield NumberField::new('ValeurCalculTheoriqueRegistre02', 'Valeur de calcul pour le registre autres collecte');
        yield NumberField::new('ValeurCalculTheoriqueCarnetRapport02', 'Valeur de calcul pour le carnet de rapport mensuel autres collecte');
         
    }
    
}
