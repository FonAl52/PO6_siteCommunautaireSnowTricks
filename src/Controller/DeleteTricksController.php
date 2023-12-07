<?php

namespace App\Controller;

use App\Entity\Tricks;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DeleteTricksController extends AbstractController
{
    /**
     * This controller allow us to delete a Ticks
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/delete/tricks', name: 'delete.tricks')]

    
    public function deleteTrick(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $slug = $request->query->get('slug');
        $tricks = $entityManager->getRepository(Tricks::class)->findOneBy(['slug' => $slug]);
        $currentUser = $this->getUser();
        $tricksCreator = $tricks->getUser();

        if ($tricksCreator !== $currentUser) {
            throw new AccessDeniedException("Vous n'avez pas l'autorisation de supprimer ce trick.");
        }

        $entityManager->remove($tricks);
        $entityManager->flush();

        return $this->redirectToRoute('home.index');
    }
}
