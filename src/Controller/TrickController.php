<?php

namespace App\Controller;

use App\Service\MediaTrick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Repository\TrickRepository;
use App\Repository\PictureRepository;
use App\Repository\VideoRepository;
use App\Form\AddTrickFormType;
use App\Form\CommentFormType;
use App\Form\EditTrickFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class TrickController extends AbstractController
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route(path: '/add', name: 'app_add_trick')]
    public function addTrick(MediaTrick $mediaTrick, Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {

        if (!$security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_404');
        }

        $user = $this->getUser();
        $trick = new Trick();
        $trick->setIdUser($user);
        $form = $this->createForm(AddTrickFormType::class, $trick, ['user' => $user]);
        $form->handleRequest($request);

        // Récupération des informations du formulaire, mise à jour des informations en base de données
        if ($form->isSubmitted()) {

            $existingTrick = $entityManager->getRepository(Trick::class)->findOneBy(['title' => $trick->getTitle()]);

            if ($existingTrick) {
                $this->addFlash('error', 'La figure avec ce titre existe déjà.');
            } elseif ($form->isValid()) {
                try {
                    $mediaTrick->handlePictures($form->get('picture')->getData(), $trick, $entityManager);
                    $mediaTrick->handleVideos($form->get('video')->getData(), $trick, $entityManager);

                    $trick->setCreatedAt(new \DateTimeImmutable());

                    $entityManager->persist($trick);
                    $entityManager->flush();

                    $this->addFlash('success', 'La figure a été ajoutée avec succès.');

                    return $this->redirectToRoute('app_main');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout du trick.');
                    $this->logger->error('Erreur d\'ajout du trick: ' . $e->getMessage());
                }
            }
        } else {
            foreach ($form->getErrors(true, true) as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        return $this->render('trick/add-trick.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/edit/{slug}', name: 'app_edit_trick')]
    public function editTrick(string $slug, EntityManagerInterface $entityManager, TrickRepository $trickRepository, Request $request, MediaTrick $mediaTrick, Security $security): Response
    {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
    
        $trick = $trickRepository->findOneBy(['slug' => $slug]);
    
        // Vérifier si l'utilisateur est le créateur du trick
        if ($this->getUser() !== $trick->getIdUser()) {
            return $this->redirectToRoute('app_404');
        }

        $form = $this->createForm(EditTrickFormType::class, $trick);
        $form->handleRequest($request);

        // Récupération des informations du formulaire, mise à jour des informations en base de données
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $newTitle = $form->get('title')->getData();
                $newSlug = (new Slugify())->slugify($newTitle);
                $trick->setSlug($newSlug);

                $mediaTrick->handlePictures($form->get('picture')->getData(), $trick, $entityManager);
                $mediaTrick->handleVideos($form->get('video')->getData(), $trick, $entityManager);

                $trick->setUpdatedAt(new \DateTimeImmutable());
                $this->addFlash('success', 'La figure a été modifiée avec succès.');

                $entityManager->flush();
            } catch (\Exception $e) {
                $this->logger->error('Une erreur est survenue lors de la modification de la figure : ' . $e->getMessage());
                $this->addFlash('error', 'Une erreur est survenue. Veuillez réessayer.');
            }

            return $this->redirectToRoute('app_edit_trick', ['slug' => $newSlug]);
        }

        // Afficher 20 images et vidéos maximum dans la page edit-trick
        $pictures = $mediaTrick->getTrickMedia($trick->getPicture(), 20);
        $videos = $mediaTrick->getTrickMedia($trick->getVideo(), 20, true);

        return $this->render('trick/edit-trick.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
            'pictureNames' => $pictures,
            'videoIds' => $videos,
            'mediaTrick' => $mediaTrick,
        ]);
    }


    #[Route(path: '/details/{slug}', name: 'app_details_trick')]
    public function detailsTrick(string $slug, EntityManagerInterface $entityManager, TrickRepository $trickRepository, Request $request, MediaTrick $mediaTrick): Response
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug]);

        if (!$trick) {
            throw $this->createNotFoundException('Trick not found');
        }

        $user = $this->getUser();

        $comment = new Comment();
        $comment->setIdTrick($trick);

        // Afficher 2 images et vidéos maximum dans la page details-trick
        $videos = $mediaTrick->getTrickMedia($trick->getVideo(), 2, true);
        $pictures = $mediaTrick->getTrickMedia($trick->getPicture(), 2);

        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        // Récupération des informations du formulaire, mise à jour des informations en base de données
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setIdUser($user);
            $comment->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Le commentaire a été ajouté avec succès.');
            return $this->redirectToRoute('app_details_trick', ['slug' => $slug]);
        }

        return $this->render('trick/details-trick.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'videoIds' => $videos,
            'pictureNames' => $pictures,
        ]);
    }

    #[Route(path: '/delete/{slug}', name: 'app_delete_trick')]
    public function deleteTrick(string $slug, EntityManagerInterface $entityManager, TrickRepository $trickRepository, Security $security): Response
    {

        if (!$security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_404');
        }

        $trick = $trickRepository->findOneBy(['slug' => $slug]);

        if ($this->getUser() !== $trick->getIdUser()) {
            return $this->redirectToRoute('app_404');
        }

        // Suppression du trick dans la base de donnée
        if ($trick) {
            $entityManager->remove($trick);
            $entityManager->flush();

            $this->addFlash('success2', 'La figure a été supprimer avec succès.');
            return $this->redirectToRoute('app_main');
        }

        return $this->render('trick/details-trick.html.twig', [
            'trick' => $trick,
        ]);
    }

    #[Route(path: '/edit/{slug}/delete-picture/{pictureId}', name: 'app_delete_picture')]
    public function deletePicture(string $slug, $pictureId, EntityManagerInterface $entityManager, PictureRepository $pictureRepository, TrickRepository $trickRepository): Response
    {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
    
        $trick = $trickRepository->findOneBy(['slug' => $slug]);
    
        // Vérifier si l'utilisateur est le créateur du trick
        if ($this->getUser() !== $trick->getIdUser()) {
            return $this->redirectToRoute('app_404');
        }

        if (!$trick) {
            throw $this->createNotFoundException('Trick not found');
        }

        $picture = $pictureRepository->find($pictureId);

        if (!$picture) {
            throw $this->createNotFoundException('Picture not found');
        }

        // Suppression d'une image dans la base de donnée
        $trick->removePicture($picture);

        $entityManager->flush();

        $this->addFlash('message', 'Image supprimée avec succès');
        return new JsonResponse(['message' => 'Image supprimée avec succès']);
    }

    #[Route(path: '/edit/{slug}/delete-video/{videoId}', name: 'app_delete_video')]
    public function deleteVideo(string $slug, $videoId, EntityManagerInterface $entityManager, VideoRepository $videoRepository, TrickRepository $trickRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
    
        $trick = $trickRepository->findOneBy(['slug' => $slug]);
    
        // Vérifier si l'utilisateur est le créateur du trick
        if ($this->getUser() !== $trick->getIdUser()) {
            return $this->redirectToRoute('app_404');
        }
        
        if (!$trick) {
            throw $this->createNotFoundException('Trick not found');
        }

        $video = $videoRepository->find($videoId);

        if (!$video) {
            throw $this->createNotFoundException('Picture not found');
        }

        // Suppression d'une vidéo dans la base de donnée
        $trick->removeVideo($video);

        $entityManager->flush();

        $this->addFlash('message', 'Video supprimée avec succès');
        return new JsonResponse(['message' => 'Video supprimée avec succès']);
    }
}
