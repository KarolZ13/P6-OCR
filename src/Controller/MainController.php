<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TrickRepository;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(TrickRepository $repo)
    {
        // Récupération des 12 tricks, des plus récents aux plus anciens
        $tricks = $repo->findBy([], ['created_at' => 'DESC'], 20, 0);

        return $this->render('main/index.html.twig', [
            'tricks' => $tricks
        ]);
    }
}
