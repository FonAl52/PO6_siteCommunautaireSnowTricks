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
use Doctrine\ORM\EntityManagerInterface;
use Cocur\Slugify\Slugify;

class CreateTricksController extends AbstractController
{
    /**
     * This controller allow us to create a Ticks
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/create/tricks', name: 'create.tricks', methods: ['GET', 'POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $tricks = new Tricks();
        $form = $this->createForm(TricksType::class, $tricks);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérez le titre depuis le formulaire
            $title = $tricks->getTitle();

            // Utilisez Slugify pour générer le slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($title);

            // Définissez le slug sur l'entité Tricks
            $tricks->setSlug($slug);

            // Récupérez les fichiers téléchargés
            $tricksImagesData = $request->files->get('tricks')['tricksImage'];

            // Parcourez les fichiers et traitez-les
            foreach ($tricksImagesData as $imageData) {
                $file = $imageData['imageFile']['file'];

                // Utilisez VichUploader pour gérer le téléchargement et l'enregistrement du fichier
                $tricksImage = new TricksImage();
                $tricksImage->setImageFile($file);

                $tricks->addTricksImage($tricksImage);
            }

            // Assurez-vous d'attribuer l'utilisateur actuellement connecté
            $user = $this->getUser();
            if ($user) {
                $tricks->setUser($user);
            }

            // Enregistrez les données dans la base de données
            $manager->persist($tricks);
            $manager->flush();

            return $this->redirectToRoute('create.tricks');
        }

        return $this->render('tricks/createTricks.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}