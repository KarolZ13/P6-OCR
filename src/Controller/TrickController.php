<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Response;

class TrickController extends AbstractController
{
    #[Route(path: '/add', name: 'app_add_trick')]
    public function addTrick(): Response
    {
        return $this->render('main/add-trick.html.twig');
    }

    #[Route(path: '/edit', name: 'app_edit_trick')]
    public function editTrick(): Response
    {
        return $this->render('main/edit-trick.html.twig');
    }

    #[Route(path: '/details', name: 'app_details_trick')]
    public function detailsTrick(): Response
    {
        return $this->render('main/details-trick.html.twig');
    }
}