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
        // Récupération des 10 figures du plus récent au plus ancien
        $tricks = $repo->findBy([], ['created_at' => 'DESC'], 10, 0);

        return $this->render('main/index.html.twig', [
            'tricks' => $tricks
        ]);
    }
}
