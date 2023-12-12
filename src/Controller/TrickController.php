<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trick;
use App\Entity\Picture;
use App\Entity\Video;
use App\Repository\TrickRepository;
use App\Form\AddTrickFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class TrickController extends AbstractController
{
    #[Route(path: '/add', name: 'app_add_trick')]
    public function addTrick(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $trick = new Trick();
        $trick->setIdUser($user);
        $form = $this->createForm(AddTrickFormType::class, $trick, ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion des images
            foreach ($trick->getPicture() as $picture) {
                $picture->setTrick($trick);
                $entityManager->persist($picture);
            }

            foreach ($form->get('picture')->getData() as $newPictureFile) {
                $newPicture = new Picture();
                $newPicture->setTrick($trick);
                $newPicture->setName($newPictureFile->getClientOriginalName());
        
                $entityManager->persist($newPicture);

                $newPictureFile->move('assets/img', $newPictureFile->getClientOriginalName());
            }

            $trick->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('app_add_trick');
        }

        return $this->render('trick\add-trick.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route(path: '/edit/{slug}', name: 'app_edit_trick')]
    public function editTrick(string $slug, EntityManagerInterface $entityManager, TrickRepository $trickRepository): Response
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug]);

        return $this->render('trick/edit-trick.html.twig', [
            'trick' => $trick,
        ]);
    }

    #[Route(path: '/details/{slug}', name: 'app_details_trick')]
    public function detailsTrick(string $slug, EntityManagerInterface $entityManager, TrickRepository $trickRepository): Response
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug]);

        return $this->render('trick/details-trick.html.twig', [
            'trick' => $trick,
        ]);
    }

    #[Route(path: '/delete/{slug}', name: 'app_delete_trick')]
    public function deleteTrick(string $slug, EntityManagerInterface $entityManager, TrickRepository $trickRepository): Response
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug]);

        return $this->render('trick/details-trick.html.twig', [
            'trick' => $trick,
        ]);
    }
}