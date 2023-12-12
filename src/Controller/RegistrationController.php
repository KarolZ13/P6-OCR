<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Création d'un jeton
            $token = md5(uniqid());
            $user->setToken($token);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            // Stocker en base de donnée les informations
            $entityManager->persist($user);
            $entityManager->flush();

            // Envoyer un mail de confirmation pour valider le compte
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('admin@snowtricks.fr', 'No Reply'))
                    ->to($user->getEmail())
                    ->subject('Veuillez vérifier votre adresse mail')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
                    ->context([
                        'signedUrl' => $this->generateUrl(
                            'app_verify_email',
                            ['token' => $token],
                            UrlGeneratorInterface::ABSOLUTE_URL
                        ),
                    ])
            );

            $this->addFlash('success', 'Veuillez vérifier votre adresse mail pour activer votre compte');
            return $this->redirectToRoute('app_main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Lien de validation par mail. 
        // Mise en place de User::isVerified=true en base de données
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
            
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }
        $this->addFlash('success', 'Votre adresse mail à bien été vérifié.');
        return $this->redirectToRoute('app_main');
    }
}
