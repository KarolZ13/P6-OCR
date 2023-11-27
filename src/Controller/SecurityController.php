<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    // Créer des controllers spécials pour ces fonctions!!!!!!!!

    #[Route(path: '/forgot-password', name: 'app_forgot_password')]
   public function forgotPassword(): Response
   {
       return $this->render('main/forgot-password.html.twig');
   }

   #[Route(path: '/details', name: 'app_details_trick')]
   public function detailsTrick(): Response
   {
       return $this->render('main/details-trick.html.twig');
   }

   #[Route(path: '/reset-password', name: 'app_reset_password')]
   public function resetPassword(): Response
   {
       return $this->render('main/reset-password.html.twig');
   }

   #[Route(path: '/modify', name: 'app_modify_trick')]
   public function modifyTrick(): Response
   {
       return $this->render('main/modify-trick.html.twig');
   }

   #[Route(path: '/add', name: 'app_add_trick')]
   public function addTrick(): Response
   {
       return $this->render('main/add-trick.html.twig');
   }

   #[Route(path: '/profil', name: 'app_user_profil')]
   public function userProfil(): Response
   {
       return $this->render('security/user-profil.html.twig');
   }
}
