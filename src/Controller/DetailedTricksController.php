<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Tricks;
use App\Entity\Comments;
use App\Form\CommentsType;
use Knp\Component\Pager\PaginatorInterface;

class DetailedTricksController extends AbstractController
{
    #[Route('/detail/tricks', name: 'detailed.tricks')]
    public function display(
        Request $request,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator
    ): Response {
        $slug = $request->query->get('slug');
        $tricks = $entityManager->getRepository(Tricks::class)->findOneBy(['slug' => $slug]);
        $currentUser = $this->getUser();

        $comments = new Comments();
        $comments->setAuthor($this->getUser())
                 ->setTricks($tricks);

        $form = $this->createForm(CommentsType::class, $comments);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comments);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire à bien été soumis ! ');
        }

        // Récupérer les commentaires sans paginer
        $commentsQuery = $entityManager->getRepository(Comments::class)->findBy(['tricks' => $tricks],
        ['id' => 'DESC']);

        // Paginer les commentaires
        $pagination = $paginator->paginate(
            $commentsQuery,
            $request->query->getInt('page', 1), // Récupérer le numéro de la page depuis la requête
            5 // Nombre d'éléments par page
        );

        return $this->render('tricks/detailedTricks.html.twig', [
            'controller_name' => 'DetailedTricksController',
            'currentUser' => $currentUser,
            'tricks' => $tricks,
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }
}
