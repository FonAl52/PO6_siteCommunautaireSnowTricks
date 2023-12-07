<?php

namespace App\Controller;

use App\Entity\Tricks;
use Cocur\Slugify\Slugify;
use App\Entity\TricksImage;
use App\Entity\TricksVideo;
use App\Form\EditTricksType;
use App\Form\TricksImageType;
use App\Form\TricksVideoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class EditTricksController extends AbstractController
{
    /**
     * This controller allow us to edit a tricks
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/edit/tricks', name: 'edit.tricks')]

    
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $slug = $request->query->get('slug');
        $tricks = $entityManager->getRepository(Tricks::class)->findOneBy(['slug' => $slug]);

        $currentUser = $this->getUser();
        $tricksCreator = $tricks->getUser()->getId();

        $form = $this->createForm(EditTricksType::class, $tricks);
        $form->handleRequest($request);
        $tricksImage = new TricksImage();

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
                }

                $photo = new TricksImage();
                $photo->setImageName($fichier);
                $tricks->addTricksImage($photo);
            }

            $tricksVideosUrls = $form->get('tricksVideo')->getData();
            if (!empty($tricksVideosUrls)) {
                $video = new TricksVideo();
                $video->setVideoUrl($tricksVideosUrls);
                $tricks->addTricksVideo($video);
            }

            $tricks->setUpdatedAtValue();

            $user = $this->getUser();
            if ($user) {
                $tricks->setUser($user);
            }

            $entityManager->persist($tricks);
            $entityManager->flush();

            $this->addFlash('success', 'Le trick a été mis à jour avec succès.');
            return $this->redirectToRoute('home.index');
        } elseif ($currentUser->getId() !== $tricksCreator) {
            $this->addFlash('warning', 'Vous n\'étes pas autorisé à modifier ce tricks.');
            return $this->redirectToRoute('home.index');
        }

        return $this->render('tricks/editTricks.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'EditTricksController',
            'currentUser' => $currentUser,
            'tricks' => $tricks,
            'image' => $tricksImage
        ]);
    }
    
    /**
     * This controller allow us to delete tricks photos
     *
     * @param EntityManagerInterface $entityManager
     * @param TricksImage $tricksImage
     * @return Response
     */
    #[Route('/delete/photos/{id}', name: 'delete.photos')]


    public function deletePhotos(
        EntityManagerInterface $entityManager,
        TricksImage $tricksImage,
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $currentUser = $this->getUser();
        $tricksCreator = $tricksImage->getTricks()->getUser();
        $tricks = $tricksImage->getTricks();
        $form = $this->createForm(EditTricksType::class, $tricks);
        if ($tricksCreator !== $currentUser) {
            $this->addFlash('warning', "Vous n'avez pas l'autorisation de supprimer cette photo.");
            return $this->redirectToRoute('security.login');
        }

        $entityManager->remove($tricksImage);
        $entityManager->flush();

        $this->addFlash('success', 'La photo a été supprimée avec succès.');
        return $this->redirectToRoute('home.index');
    }

    /**
     * This controller allow us to update tricks photos
     *
     * @param Request $request,
     * @param EntityManagerInterface $entityManager
     * @param TricksImage $photo
     * @return Response
     */
    #[Route('/update/photos/{id}', name: 'update.photos', methods: ['POST', 'GET'])]


    public function updatePhotos(
        Request $request,
        EntityManagerInterface $entityManager,
        TricksImage $photo,
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $tricks = $photo->getTricks();

        $currentUser = $this->getUser();

        $photoForm = $this->createForm(TricksImageType::class, $photo);

        $photoForm->handleRequest($request);

        if ($photoForm->isSubmitted() && $photoForm->isValid()) {

            $tricks->setUpdatedAtValue();
            $entityManager->persist($tricks);
            $entityManager->flush();

            $this->addFlash('success', 'La photo a été mise à jour avec succès.');

            return $this->redirectToRoute('edit.tricks', ['slug' => $tricks->getSlug()]);
        }
        $showUpdatePhotoForm = true;

        return $this->render('tricks/editTricks.html.twig', [
            'currentUser' => $currentUser,
            'photoForm' => $photoForm->createView(),
            'tricks' => $tricks,
            'showUpdatePhotoForm' => $showUpdatePhotoForm
        ]);
    }

    /**
     * This controller allow us to delete tricks video
     *
     * @param EntityManagerInterface $entityManager
     * @param TricksVideo $video
     * @return Response
     */
    #[Route('/delete/videos/{id}', name: 'delete.videos')]


    public function deleteVideos(
        EntityManagerInterface $entityManager,
        TricksVideo $video
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $currentUser = $this->getUser();
        $tricksCreator = $video->getTricks()->getUser();
        $tricks = $video->getTricks();
        $form = $this->createForm(EditTricksType::class, $tricks);
        if ($tricksCreator !== $currentUser) {
            $this->addFlash('warning', "Vous n'avez pas l'autorisation de supprimer cette photo.");
            return $this->redirectToRoute('security.login');
        }

        $entityManager->remove($video);
        $entityManager->flush();

        $this->addFlash('success', 'La vidéo a été supprimée avec succès.');
        return $this->redirectToRoute('home.index');
    }

    /**
     * This controller allow us to update tricks video
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TricksVideo $video
     * @return Response
     */
    #[Route('/update/videos/{id}', name: 'update.videos', methods: ['POST', 'GET'])]


    public function updateVideos(
        Request $request,
        EntityManagerInterface $entityManager,
        TricksVideo $video,
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $tricks = $video->getTricks();
        $currentUser = $this->getUser();
        $tricksCreator = $video->getTricks()->getUser();
        $videoForm = $this->createForm(TricksVideoType::class, $video);

        $videoForm->handleRequest($request);

        if ($tricksCreator !== $currentUser) {
            $this->addFlash('warning', "Vous n'avez pas l'autorisation de supprimer cette vidéo.");
            return $this->redirectToRoute('home.index');
        }


        if ($videoForm->isSubmitted() && $videoForm->isValid()) {

            $tricksVideosUrls = $videoForm->get('tricksVideo')->getData();

            if (!empty($tricksVideosUrls)) {
                $video->setVideoUrl($tricksVideosUrls);
            }

            $tricks->setUpdatedAtValue($video);
            $entityManager->persist($tricks);
            $entityManager->flush();

            $this->addFlash('success', 'La vidéo a été mise à jour avec succès.');

            return $this->redirectToRoute('edit.tricks', ['slug' => $tricks->getSlug()]);
        }

        $showUpdateVideoForm = true;

        return $this->render('tricks/editTricks.html.twig', [
            'currentUser' => $currentUser,
            'videoForm' => $videoForm->createView(),
            'tricks' => $tricks,
            'showUpdateVideoForm' => $showUpdateVideoForm
        ]);
    }
}
