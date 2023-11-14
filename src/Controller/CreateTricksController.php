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
use App\Entity\TricksVideo;
use Doctrine\ORM\EntityManagerInterface;
use Cocur\Slugify\Slugify;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

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
            $tricks->addTricksImage($photo);
            
            // Récupérer la chaîne d'URLs du formulaire
            $tricksVideosUrls = $form->get('tricksVideo')->getData();

            // Diviser la chaîne en un tableau en utilisant les espaces et virgules comme délimiteurs
            $urlsArray = explode(', ', $tricksVideosUrls);

            foreach ($urlsArray as $url) {
                // Vérifier si l'URL commence par "https://"
                if (strpos($url, 'https://') === 0) {
                    // Créer une nouvelle instance de TricksVideo
                    $video = new TricksVideo();
                    $video->setVideoUrl($url);
                
                    // Ajouter le TricksVideo au Tricks
                    $tricks->addTricksVideo($video);  
                }
            }
           
            $user = $this->getUser();
            if ($user) {
                $tricks->setUser($user);
            }
    
            $manager->persist($tricks);
            $manager->flush();
    
            return $this->redirectToRoute('home.index');
        }
    
        return $this->render('tricks/createTricks.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}