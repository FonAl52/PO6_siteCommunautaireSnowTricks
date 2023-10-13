<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TricksType;
use App\Form\TricksImageType;
use App\Entity\Tricks;
use App\Entity\TricksImage;

class CreateTricksController extends AbstractController
{
    #[Route('/create/tricks', name: 'create.tricks')]
    public function create(Request $request): Response
    {
        $tricks = new Tricks();
    
        $form = $this->createForm(TricksType::class, $tricks);
       
        

        

        return $this->render('tricks/createTricks.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}