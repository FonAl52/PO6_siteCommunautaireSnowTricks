<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateTricksController extends AbstractController
{
    #[Route('/create/tricks', name: 'create.tricks')]
    public function index(): Response
    {
        return $this->render('tricks/createTricks.html.twig', [
            'controller_name' => 'CreateTricksController',
        ]);
    }
}
