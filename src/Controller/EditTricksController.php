<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Tricks;
use App\Entity\User;
use App\Form\EditTricksType;
use Symfony\Bundle\SecurityBundle\Security;

class EditTricksController extends AbstractController
{
    #[Route('/edit/tricks', name: 'edit.tricks')]
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
       
        $slug = $request->query->get('slug');
        $tricks = $entityManager->getRepository(Tricks::class)->findOneBy(['slug' => $slug]);
        $currentUser = $this->getUser();
        $tricksCreator = $tricks->getUser()->getId();
        
        $form = $this->createForm(EditTricksType::class, $tricks);

        $form->handleRequest($request);

        if ($currentUser->getId() === $tricksCreator){
            if ($form->isSubmitted() && $form->isValid()) {
                // Gérez la logique de mise à jour des données ici
                $entityManager->flush();
                $this->addFlash('success', 'Le trick a été mis à jour avec succès.');
            }
    
            return $this->render('tricks/editTricks.html.twig', [
                'form' => $form->createView(),
                'controller_name' => 'EditTricksController',
                'currentUser' => $currentUser,
                'tricks' => $tricks,
            ]);
        } else {
            $this->addFlash('warning', 'Vous n\'étes pas autorisé à modifier ce tricks.');
            return $this->redirectToRoute('home.index');
        }

        
    }    
}
