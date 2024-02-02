<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(TrickRepository $repo)
    {
        // Récupération de 20 tricks, des plus récents aux plus anciens
        $tricks = $repo->findBy([], ['created_at' => 'DESC'], 20, 0);

        return $this->render('main/index.html.twig', [
            'tricks' => $tricks
        ]);
    }

    #[Route(path: '/404', name: 'app_404')]
    public function error404(): Response
    {
        return $this->render('404.html.twig');
    }
}
