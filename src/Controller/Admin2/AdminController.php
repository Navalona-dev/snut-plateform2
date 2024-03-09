<?php

namespace App\Controller\Admin2;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\AccesService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class AdminController extends AbstractController
{

    private $accesService;

    #[Route('/admin', name: 'admin', methods: ['GET'])]
    public function index(Request $request)
    {
       /* $token['token'] = "xxx";
        $email = (new TemplatedEmail())
        ->from(new Address('mailer@snut.mg', 'snut'))
        ->to("nnavalona@gmail.com")
        ->subject('Your password reset request')
        ->htmlTemplate('reset_password/email.html.twig')
        ->context([
            'resetToken' => $token,
        ])
    ;*/
    
        return $this->render('admin/dashboard/index.html.twig', []);
    }

    /**
     * @Route("/admin/login", name="app_admin_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('admin');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'user' => 'administrateur']);
    }
}