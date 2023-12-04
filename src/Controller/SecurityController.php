<?php

namespace App\Controller;

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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Mailer\MailerInterface;

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
        $user = $userRepository->findOneBy(['token' => $token]);
    
        if ($user === null) {
            $this->addFlash('danger', 'Token inconnu');
            return $this->redirectToRoute('app_login');
        }
    
        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('password')->getData();
            $hashPassword = $userPasswordHasher->hashPassword($user, $newPassword);
    
            $user->setPassword($hashPassword);
            $user->setToken(null);
    
            $entityManager->persist($user);
            $entityManager->flush();
    
            $this->addFlash('success', 'Mot de passe mis à jour avec succès.');
            return $this->redirectToRoute('app_login');
        }
    
        return $this->render('main/reset-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route(path: '/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, MailerInterface $mailer): Response {
        $form = $this->createForm(ForgotPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $userRepository->findOneByUsername($data['username']);

            if ($user === null) {
                $this->addFlash('danger', 'Cet utilisateur est inconnue.');
                return $this->redirectToRoute('app_forgot_password');
            }

            $token = md5(uniqid());
            $user->setToken($token);
            $entityManager->persist($user);
            $entityManager->flush();

            $resetLink = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
            $email = (new TemplatedEmail())
                ->from(new Address('admin@snowtricks.fr', 'No Reply'))
                ->to($user->getEmail())
                ->subject('Réinitialisation du mot de passe')
                ->htmlTemplate('registration/reset_password_email.html.twig')
                ->context([
                    'resetLink' => $resetLink,
                ]);

            $mailer->send($email);

            $this->addFlash('success', 'Veuillez vérifier votre adresse mail !');
            return $this->redirectToRoute('app_forgot_password');
        }
       return $this->render('main/forgot-password.html.twig', [
        'form' => $form->createView(),
       ]);
   }

   #[Route(path: '/profil', name: 'app_user_profil')]
   public function userProfil(): Response
   {
       return $this->render('security/user-profil.html.twig');
   }
}
