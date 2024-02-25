<?php

namespace App\Controller\Admin;

use App\Entity\AnneePrevisionnelle;
use App\Entity\CentreHospitalierUniversitaire;
use App\Entity\CentreRecuperation;
use App\Entity\CommandeSemestrielle;
use App\Entity\CommandeTrimestrielle;
use App\Entity\CreniMoisProjectionsAdmissions;
use App\Entity\District;
use App\Entity\Groupe;
use App\Entity\MoisProjectionsAdmissions;
use App\Entity\Produit;
use App\Entity\Province;
use App\Entity\Region; 
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin_old', name: 'admin_dashboard_index')]
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');

        return $this->render('admin-old/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SNUT Plateforme');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home'); 
        yield MenuItem::subMenu('Paramétrages de localisation', 'fas fa-map-marker')->setSubItems([
            MenuItem::linkToCrud('Provinces', 'fa fa-circle-o', Province::class),
            MenuItem::linkToCrud('Régions', 'fa fa-circle-o', Region::class),
            MenuItem::linkToCrud('Districts', 'fa fa-circle-o', District::class), 
            MenuItem::linkToCrud('Centre Hospitlier Universitaire', 'fa fa-circle-o', CentreHospitalierUniversitaire::class)
        ]);

        yield MenuItem::subMenu('Paramétrages de Centres', 'fas fa-podcast')->setSubItems([
            MenuItem::linkToCrud('Centres de recupération', 'fa fa-circle-o', CentreRecuperation::class),
        ]);

        yield MenuItem::subMenu('Gestion des produits', 'fas fa-podcast')->setSubItems([
            MenuItem::linkToCrud('listes', 'fa fa-circle-o', Produit::class),
        ]);

        yield MenuItem::subMenu('Paramétrages de bon de commande CRENAS', 'fas fa-clipboard')->setSubItems([
            MenuItem::linkToCrud('Année previsionnelle', 'fa fa-circle-o', AnneePrevisionnelle::class),
            MenuItem::linkToCrud('Groupes', 'fa fa-circle-o', Groupe::class),
            MenuItem::linkToCrud('Date de Commande', 'fa fa-circle-o', CommandeTrimestrielle::class),
            MenuItem::linkToCrud('Mois de projection et admission', 'fa fa-circle-o', MoisProjectionsAdmissions::class),
        ]);

        yield MenuItem::subMenu('Paramétrages de bon de commande CRENI', 'fas fa-clipboard')->setSubItems([
            MenuItem::linkToCrud('Date de Commande', 'fa fa-circle-o', CommandeSemestrielle::class),
            MenuItem::linkToCrud('Mois de projection et admission', 'fa fa-circle-o', CreniMoisProjectionsAdmissions::class)
        ]);
    }
}
