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
use App\Entity\TricksImage;
use App\Entity\TricksVideo;
use Symfony\Bundle\SecurityBundle\Security;
use Cocur\Slugify\Slugify;

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

        if ($currentUser->getId() === $tricksCreator && $form->isSubmitted() && $form->isValid()) {
            $title = $tricks->getTitle();

            $slugify = new Slugify();
            $slug = $slugify->slugify($title);

            $tricks->setSlug($slug);

            $medias = $form->get('TriksImage')->getData();
            foreach ($medias as $media) {
                $fichier = md5(uniqid()) . '.' . $media->guessExtension();
                try {
                    $media->move(
                        $this->getParameter('trick_img_directory'),
                        $fichier
                    );
                } catch (FileException $e) {
                    //
                }

                $photo = new TricksImage();
                $photo->setImageName($fichier);
                $tricks->addTricksImage($photo);
            }

            $tricksVideosUrls = $form->get('tricksVideo')->getData();
            $video = new TricksVideo();
            $video->setVideoUrl($tricksVideosUrls);
            $tricks->addTricksVideo($video);

            $tricks->setUpdatedAtValue();

            $user = $this->getUser();
            if ($user) {
                $tricks->setUser($user);
            }

            $entityManager->persist($tricks);
            $entityManager->flush();

            $this->addFlash('success', 'Le trick a été mis à jour avec succès.');
        } elseif ($currentUser->getId() !== $tricksCreator) {
            $this->addFlash('warning', 'Vous n\'étes pas autorisé à modifier ce tricks.');
            return $this->redirectToRoute('home.index');
        }

        return $this->render('tricks/editTricks.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'EditTricksController',
            'currentUser' => $currentUser,
            'tricks' => $tricks,
        ]);
    }
       
    #[Route('/delete/photos/{id}', name: 'delete.photos')]
    public function deletePhotos(
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security,
        TricksImage $tricksImage
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        $currentUser = $this->getUser();
        $tricksCreator = $tricksImage->getTricks()->getUser();
        
        if ($tricksCreator !== $currentUser) {
            $this->addFlash('warning', "Vous n'avez pas l'autorisation de supprimer cette photo.");
        }

        $entityManager->remove($tricksImage);
        $entityManager->flush();

        $this->addFlash('success', 'La photo a été supprimée avec succès.');
        return $this->redirectToRoute('home.index');
    }

    #[Route('/delete/videos/{id}', name: 'delete.videos')]
    public function deleteVideos(
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security,
        TricksVideo $tricksVideo
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $currentUser = $this->getUser();
        $tricksCreator = $tricksVideo->getTricks()->getUser();
        
        if ($tricksCreator !== $currentUser) {
            $this->addFlash('warning', "Vous n'avez pas l'autorisation de supprimer cette vidéo.");
        }

        $entityManager->remove($tricksVideo);
        $entityManager->flush();

        $this->addFlash('success', 'La vidéo a été supprimée avec succès.');
        return $this->redirectToRoute('home.index');
    }
}
