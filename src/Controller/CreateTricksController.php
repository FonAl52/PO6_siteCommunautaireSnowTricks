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
           
            $tricksVideosData = $form->get('tricksVideo')->getData();

            foreach ($tricksVideosData as $videoData) {

                $tricksVideo = new TricksVideo();
                $tricksVideo->setTricks($tricks);
                
                $manager->persist($tricksVideo);
            }
    
            $user = $this->getUser();
            if ($user) {
                $tricks->setUser($user);
            }
    
            $manager->persist($tricks);
            $manager->flush();
    
            return $this->redirectToRoute('create.tricks');
        }
    
        return $this->render('tricks/createTricks.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}