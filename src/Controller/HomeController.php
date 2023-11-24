<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Tricks;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home.index', methods: ['GET'])]
    public function index(
        EntityManagerInterface $entityManager
        ): Response {
        $tricksRepository = $entityManager->getRepository(Tricks::class);
        $tricks = $tricksRepository->findBy([], ['createdAt' => 'DESC']);
        $currentUser = $this->getUser();
    
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
            'currentUser' => $currentUser,
            'tricks' => $tricks,
        ]);
    }    
}