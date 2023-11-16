<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Tricks;
use Knp\Component\Pager\PaginatorInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home.index', methods: ['GET'])]
    public function index(
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        Request $request
        ): Response {
        $tricksRepository = $entityManager->getRepository(Tricks::class);
        $data = $tricksRepository->findAll();
        $tricks = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            15
        );

        $currentUser = $this->getUser();
    
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
            'currentUser' => $currentUser,
            'tricks' => $tricks,
        ]);
    }    
}
