<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditTricksController extends AbstractController
{
    #[Route('/edit/tricks', name: 'edit.tricks')]
    public function index(): Response
    {
        return $this->render('tricks/editTricks.html.twig', [
            'controller_name' => 'EditFigureController',
        ]);
    }
}
