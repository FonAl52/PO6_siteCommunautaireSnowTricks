<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Tricks;

class DetailedTricksController extends AbstractController
{
    #[Route('/detail/tricks', name: 'detailed.tricks')]
    public function display(
        Request $request,
        EntityManagerInterface $entityManager
        ): Response {
        $slug = $request->query->get('slug');
        $tricks = $entityManager->getRepository(Tricks::class)->findOneBy(['slug' => $slug]);
        $currentUser = $this->getUser();
        
        return $this->render('tricks/detailedTricks.html.twig', [
            'controller_name' => 'DetailedTricksController',
            'currentUser' => $currentUser,
            'tricks' => $tricks,
        ]);
    }    

}
