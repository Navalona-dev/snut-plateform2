<?php

namespace App\Controller\Admin2;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Service\UserService;
use App\Service\AccesService;
use App\Service\EmailService;

class AdminUserController extends AbstractController
{
  private $accesService;
  

  public function __construct()
  {
    
    
  }

  /**
   * @Route("/admin/users", name="admin_users_liste")
   */
  public function index()
  {
    return $this->render('admin_user/index.html.twig', [
      'controller_name' => 'AdminUserController',
    ]);
  }

  /**
   * @Route("/admin/users/ajax", name="admin_users_liste_ajax")
   */
  public function liste(Request $request, UserService $UserService)
  {
    $draw = intval($request->request->get('draw'));
    $start = $request->request->get('start');
    $length = $request->request->get('length');
    $search = $request->request->get('search');
    $orders = $request->request->get('order');
    $columns = $request->request->get('columns');
    $recherche = "AAAA";
    /* if (isset($search) && !is_null($search) && !empty($search)) {
          $recherche = $search;
        }*/
    /*$response = '{
            "draw": '.$draw.',
            "recordsTotal": 21,
            "recordsFiltered": 21,
            "data": [
              {

                "nom" : '.$length.',
                "telephone" : '.$draw.',
                "email" : "'.$recherche.'",
                "id": "1"
              },
              {

                "nom" : "Angelica",
                "telephone" : "Ramos",
                "email" : "Chief Executive Officer (CEO)",
                "id": "2"
              },
              {

                "nom" : "Ashton",
                "telephone" : "Cox",
                "email" : "Junior Technical Author",
                "id": "3"
              },
              {

                "nom" : "Angelica",
                "telephone" : "Ramos",
                "email" : "Chief Executive Officer (CEO)",
                "id": "2"
              },
              {

                "nom" : "Angelica",
                "telephone" : "Ramos",
                "email" : "Chief Executive Officer (CEO)",
                "id": "2"
              },
              {

                "nom" : "Angelica",
                "telephone" : "Ramos",
                "email" : "Chief Executive Officer (CEO)",
                "id": "2"
              },
              {

                "nom" : "Angelica",
                "telephone" : "Ramos",
                "email" : "Chief Executive Officer (CEO)",
                "id": "2"
              },
              {

                "nom" : "Angelica",
                "telephone" : "Ramos",
                "email" : "Chief Executive Officer (CEO)",
                "id": "2"
              },
              {

                "nom" : "Angelica",
                "telephone" : "Ramos",
                "email" : "Chief Executive Officer (CEO)",
                "id": "2"
              },
              {

                "nom" : "Angelica",
                "telephone" : "Ramos",
                "email" : "Chief Executive Officer (CEO)",
                "id": "2"
              },
              {

                "nom" : "Angelica",
                "telephone" : "Ramos",
                "email" : "Chief Executive Officer (CEO)",
                "id": "2"
              },
              {

                "nom" : "Angelica",
                "telephone" : "Ramos",
                "email" : "Chief Executive Officer (CEO)",
                "id": "2"
              },
              {

                "nom" : "Angelica",
                "telephone" : "Ramos",
                "email" : "Chief Executive Officer (CEO)",
                "id": "2"
              },
              {

                "nom" : "Angelica",
                "telephone" : "Ramos",
                "email" : "Chief Executive Officer (CEO)",
                "id": "2"
              },
              {

                "nom" : "Angelica",
                "telephone" : "Ramos",
                "email" : "Chief Executive Officer (CEO)",
                "id": "2"
              },
              {

                "nom" : "Angelica",
                "telephone" : "Ramos",
                "email" : "Chief Executive Officer (CEO)",
                "id": "2"
              },
              {

                "nom" : "Angelica",
                "telephone" : "Ramos",
                "email" : "Chief Executive Officer (CEO)",
                "id": "2"
              },
              {

                "nom" : "Angelica",
                "telephone" : "Ramos",
                "email" : "Chief Executive Officer (CEO)",
                "id": "2"
              },
              {

                "nom" : "Angelica",
                "telephone" : "Ramos",
                "email" : "Chief Executive Officer (CEO)",
                "id": "2"
              },
              {

                "nom" : "Angelica",
                "telephone" : "Ramos",
                "email" : "Chief Executive Officer (CEO)",
                "id": "2"
              },
              {

                "nom" : "Angelica",
                "telephone" : "Ramos",
                "email" : "Chief Executive Officer (CEO)",
                "id": "2"
              }
            ]
          }';*/
    $recherche = "";
    if ($search['value'] != "") {
      $recherche = $search['value'];
    }

    $users = $UserService->getAllUser((int) $start, (int) $length, $recherche);
    $total = $UserService->getNombreTotalUser();
    $recordsTotal = (($total > 0) ? $total : 0);
    $tabUsers = [];
    $tabUsers["draw"] = $draw;
    $tabUsers["recordsTotal"] = $recordsTotal;
    $tabUsers["recordsFiltered"] = $recordsTotal;
    $tabUsers["data"] = [];

    foreach ($users as $key => $user) {
      $InfoUser = [];
      $InfoUser['nom'] = $user['nom'];
      $InfoUser['telephone'] = $user['telephone'];
      $InfoUser['email'] = $user['email'];
      $InfoUser['id'] = $user['id'];
      if (!in_array($InfoUser, $tabUsers["data"])) {
        array_push($tabUsers["data"], $InfoUser);
      }
    }

    return new JsonResponse($tabUsers);
    /*return $this->render('admin_user/index.html.twig', [
            'controller_name' => 'AdminUserController',
        ]);*/
  }

  /**
   * @Route("/admin/users/edit/{user}", name="admin_user_edit")
   */
  /*public function edit(Request $request, UserService $UserService, User $user, EmailService $EmailService)
  {
    if (!$this->accesService->insufficientPrivilege('oatf')) {
      return $this->redirectToRoute('app_logout'); // To DO page d'alerte insufisance privilege
    }
    $stateActivationBefore = $user->getIsActive();
    $form = $this->createForm(UserType::class, $user, ['isTraitement' => true, 'isAdmin' => true]);
    $form->handleRequest($request);



    if ($form->isSubmitted() && $form->isValid()) {
        // changement etat

        if ($stateActivationBefore != $user->getIsActive()) {

            if (!$stateActivationBefore && $user->getIsActive()) { // activation compte, envoie mail
              $data = [];
              
              $user->setActivationToken(md5(uniqid()));
              $data['token'] = $user->getActivationToken();

              //$EmailService->sendEmail('oatf@matac.com', $user->getEmail(), 'Bienvenue', 'security/activation.html.twig', $data);
              
          }

          /*if (!$user->getIsActive() && !is_null($user->getPassword())) { // desactivation compte, envoie mail

          }*/
  /*      }

      $UserService->setUser($user);
      $UserService->update();

      return $this->redirectToRoute('admin_users_liste');
    }
    return $this->render('admin_user/edit.html.twig', [
      'form' => $form->createView(),
      'id' => $user->getId(),
    ]);
  }*/
}