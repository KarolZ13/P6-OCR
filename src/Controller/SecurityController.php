<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use App\Form\ForgotPasswordFormType;
use App\Form\ResetPasswordFormType;
use App\Form\UserFormType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;

class SecurityController extends AbstractController
{

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/reset-password/{token}', name: 'app_reset_password')]
    public function resetPassword(UserRepository $userRepository, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, string $token): Response
    {
        // Suite à l'envoi de mail, vérification du token selon l'utilisateur
        $user = $userRepository->findOneBy(['token' => $token]);
    
        // Si l'utilisateur n'est pas trouvé
        if ($user === null) {
            $this->addFlash('danger', 'Token inconnu');
            return $this->redirectToRoute('app_login');
        }
    
        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);
    
        // Récupération des informations du formulaire, mise à jour des informations en base de données
        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('password')->getData();
            $hashPassword = $userPasswordHasher->hashPassword($user, $newPassword);
    
            $user->setPassword($hashPassword);
            $user->setToken(null);
    
            $entityManager->persist($user);
            $entityManager->flush();
    
            $this->addFlash('success', 'Mot de passe mis à jour avec succès.');
            return $this->redirectToRoute('app_main');
        }
    
        return $this->render('security/reset-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route(path: '/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, MailerInterface $mailer): Response {
        $form = $this->createForm(ForgotPasswordFormType::class);
        $form->handleRequest($request);

        // Récupération des informations du formulaire, mise à jour des informations en base de données
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $userRepository->findOneByUsername($data['username']);

            if ($user === null) {
                $this->addFlash('danger', 'Cet utilisateur est inconnue.');
                return $this->redirectToRoute('app_forgot_password');
            }

            // Générer un jeton pour l'utilisateur et stocker en base de données
            $token = md5(uniqid());
            $user->setToken($token);
            $entityManager->persist($user);
            $entityManager->flush();

            // Générer un lien avec le jeton pour réinitialiser le mot de passe
            $resetLink = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
            $email = (new TemplatedEmail())
                ->from(new Address('admin@snowtricks.fr', 'No Reply'))
                ->to($user->getEmail())
                ->subject('Réinitialisation du mot de passe')
                ->htmlTemplate('security/reset_password_email.html.twig')
                ->context([
                    'resetLink' => $resetLink,
                ]);

            $mailer->send($email);

            $this->addFlash('success', 'Veuillez vérifier votre adresse mail !');
            return $this->redirectToRoute('app_forgot_password');
        }
       return $this->render('security/forgot-password.html.twig', [
        'form' => $form->createView(),
       ]);
    }

    #[Route(path: '/profil', name: 'app_user_profil')]
    public function userProfil(Request $request, Security $security, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $security->getUser();
        
        if ($user instanceof User) {
            $form = $this->createForm(UserFormType::class, $user);
   
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                
                $newPassword = $form->get('plainPassword')->getData();
                $hashPassword = $userPasswordHasher->hashPassword($user, $newPassword);
        
                $user->setPassword($hashPassword);
        
                $entityManager->persist($user);
                $entityManager->flush();
   
                $this->addFlash('success', 'Profil mis à jour avec succès.');
            }
   
            return $this->render('main\user-profil.html.twig', [
               'form' => $form->createView(),
               'user' => $user,
            ]);
        }
        return $this->redirectToRoute('app_login');
    }
   
}
