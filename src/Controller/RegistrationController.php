<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\RegistrationSupervisorCentralFormType;
use App\Form\RegistrationSupervisorRegionalFormType;
use App\Security\UsersAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); // Supposons que cela retourne l'utilisateur actuellement connecté.
        if ($user) {
            return $this->redirectToRoute('app_accueil');
        } else {
            $user = new User();
            $user->addRole('ROLE_AGENT_DISTRICT');
            $form = $this->createForm(RegistrationFormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $entityManager->persist($user);
                $entityManager->flush();
                // do anything else you need here, like send an email

                return $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                );
            }

            return $this->render('account/register.html.twig', [
                'registrationForm' => $form->createView(),
            ]);
        }
    }

    #[Route('/inscription-superviseur-regional', name: 'app_register_supervisor_regional')]
    public function regionalSupervisorRegistration(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); // Supposons que cela retourne l'utilisateur actuellement connecté.
        if ($user) {
            return $this->redirectToRoute('app_accueil');
        } else {
            $user = new User();
            $user->addRole('ROLE_REGIONAL_SUPERVISOR');
            $form = $this->createForm(RegistrationSupervisorRegionalFormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $entityManager->persist($user);
                $entityManager->flush();
                // do anything else you need here, like send an email

                return $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                );
            }

            return $this->render('account/registerSupervisorRegional.html.twig', [
                'registrationSupervisorRegionalForm' => $form->createView(),
            ]);
        }
    }

    #[Route('/inscription-superviseur-central', name: 'app_register_supervisor_central')]
    public function centralSupervisorRegistration(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); // Supposons que cela retourne l'utilisateur actuellement connecté.
        if ($user) {
            return $this->redirectToRoute('app_accueil');
        } else {
            $user = new User();
            $user->addRole('ROLE_CENTRAL_SUPERVISOR');
            $form = $this->createForm(RegistrationSupervisorCentralFormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $entityManager->persist($user);
                $entityManager->flush();
                // do anything else you need here, like send an email

                return $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                );
            }

            return $this->render('account/registerSupervisorCentral.html.twig', [
                'registrationSupervisorCentralForm' => $form->createView(),
            ]);
        }
    }
}
