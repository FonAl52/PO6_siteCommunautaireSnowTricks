<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetailedTricksController extends AbstractController
{
    #[Route('/detail/tricks', name: 'detailed.tricks')]
    public function index(): Response
    {
        return $this->render('tricks/detailedTricks.html.twig', [
            'controller_name' => 'DetailedTricksController',
        ]);
    }
}
