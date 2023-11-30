<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Group;
use App\Entity\TricksImage;
use App\Entity\TricksVideo;
use App\Entity\Tricks;
use App\Entity\Comments;
use Faker\Generator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Component\HttpFoundation\File\File;
use Cocur\Slugify\Slugify;

class AppFixtures extends Fixture
{  
    /**
     * @var Generator
     */
    private Generator $faker;
    private UploaderHelper $uploaderHelper;

    public function __construct(UploaderHelper $uploaderHelper)
    {
        $this->faker = Factory::create('fr_FR');
        $this->uploaderHelper = $uploaderHelper;
    }

    public function load(ObjectManager $manager): void
    {
        
        // Users
        $users = [];

        $admin = new User();
        $admin->setLastName('admin')
            ->setFirstName('admin')
            ->SetEmail('admin@snowtricks.fr')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setPlainPassword('password');

        $users[] = $admin;
        $manager->persist($admin);

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setLastName($this->faker->lastName())
                ->setFirstName($this->faker->firstName())
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');

            $users[] = $user;
            $manager->persist($user);
        }

        // Group
        $groups = [];
        for ($i = 0; $i < 10; $i++) {
            $group = new Group();
            $group->setName($this->faker->word());

            $groups[] = $group;
            $manager->persist($group);
        }

        // Tricks
        $tricks = [];

        // Mute
        $mute = new Tricks();
        $mute->setTitle('Mute')
            ->setDescription('Saisie de la carre frontside de la planche entre les deux pieds avec la main avant.')
            ->setUser($users[array_rand($users)]);
            $slugify = new Slugify();
            $slug = $slugify->slugify('Mute');
    
        $mute->setSlug($slug);
        $tricksImage = new TricksImage();
        $tricksImage->setImageName('mute/FeatCollection2-SnowShop_SP.webp');
        $mute->addTricksImage($tricksImage);
        
        $tricksImage2 = new TricksImage();
        $tricksImage2->setImageName('mute/mute-air.webp');
        $mute->addTricksImage($tricksImage2);
        
        $tricksImage3 = new TricksImage();
        $tricksImage3->setImageName('mute/mute-air (1).webp');
        $mute->addTricksImage($tricksImage3);
            
        $video = new TricksVideo();
        $video->setVideoUrl('https://www.youtube.com/embed/k6aOWf0LDcQ?si=AlQGFif-J1gfCXt_');
 
        $mute->addTricksVideo($video);  
        $manager->persist($mute);
        $tricks[] = $mute;

        // Nose grab
        $noseGrab = new Tricks();
        $noseGrab->setTitle('Nose grab')
            ->setDescription('Saisie de la partie avant de la planche, avec la main avant.')
            ->setUser($users[array_rand($users)]);
            $slugify = new Slugify();
            $slug = $slugify->slugify('Nose grab');
    
        $noseGrab->setSlug($slug);
        $tricksImage = new TricksImage();
        $tricksImage->setImageName('nose grab/nose-grab-snowboard (1).webp');
        $noseGrab->addTricksImage($tricksImage);
        
        $tricksImage2 = new TricksImage();
        $tricksImage2->setImageName('nose grab/nose-grab-snowboarding.webp');
        $noseGrab->addTricksImage($tricksImage2);
            
        $video = new TricksVideo();
        $video->setVideoUrl('https://www.youtube.com/embed/nIS14rVlbyQ?si=fEu9nhpT4sN8Mi71');
 
        $noseGrab->addTricksVideo($video);  
        $manager->persist($noseGrab);

        $tricks[] = $noseGrab;

        // Sad
        $sad = new Tricks();
        $sad->setTitle('Sad')
            ->setDescription('Saisie de la carre backside de la planche, entre les deux pieds, avec la main avant.')
            ->setUser($users[array_rand($users)]);
            $slugify = new Slugify();
            $slug = $slugify->slugify('Sad');
    
        $sad->setSlug($slug);
        $tricksImage = new TricksImage();
        $tricksImage->setImageName('sad/freestyler1.webp');
        $sad->addTricksImage($tricksImage);
        
        $tricksImage2 = new TricksImage();
        $tricksImage2->setImageName('sad/indy-grab-snowboarding.webp');
        $sad->addTricksImage($tricksImage2);
            
        $video = new TricksVideo();
        $video->setVideoUrl('https://www.youtube.com/embed/0kDG0PZM36w?si=pm5ijO7m1k0RUqE');
 
        $sad->addTricksVideo($video);  
        $manager->persist($sad);

        $tricks[] = $sad;

        // Indy
        $indy = new Tricks();
        $indy->setTitle('Indy')
            ->setDescription('Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière.')
            ->setUser($users[array_rand($users)]);
            $slugify = new Slugify();
            $slug = $slugify->slugify('Indy');
    
        $indy->setSlug($slug);
        $tricksImage = new TricksImage();
        $tricksImage->setImageName('indy/chicken-salad-grab-snowboard.webp');
        $indy->addTricksImage($tricksImage);
        
        $tricksImage2 = new TricksImage();
        $tricksImage2->setImageName('indy/indy-grab-snowboard.webp');
        $indy->addTricksImage($tricksImage2);

        $tricksImage3 = new TricksImage();
        $tricksImage3->setImageName('indy/indy-grab.webp');
        $indy->addTricksImage($tricksImage3);
            
        $video = new TricksVideo();
        $video->setVideoUrl('https://www.youtube.com/embed/6yA3XqjTh_w?si=3zWr-qHXpP_t2iNY');
 
        $indy->addTricksVideo($video);  
        $manager->persist($indy);

        $tricks[] = $indy;

        // Stalefish
        $stalefish = new Tricks();
        $stalefish->setTitle('Stalefish')
            ->setDescription('Saisie de la carre backside de la planche entre les deux pieds avec la main arrière.')
            ->setUser($users[array_rand($users)]);
            $slugify = new Slugify();
            $slug = $slugify->slugify('Stalefish');

        $stalefish->setSlug($slug);
        $tricksImage = new TricksImage();
        $tricksImage->setImageName('stalefish/rome-stale-crewzer-2022-rome-snowboard.webp');
        $stalefish->addTricksImage($tricksImage);

        $tricksImage2 = new TricksImage();
        $tricksImage2->setImageName('stalefish/stale-sandbech-lines-sml.1554773134.webp');
        $stalefish->addTricksImage($tricksImage2);

        $tricksImage3 = new TricksImage();
        $tricksImage3->setImageName('stalefish/stalefish-grab.webp');
        $stalefish->addTricksImage($tricksImage3);
            
        $video = new TricksVideo();
        $video->setVideoUrl('https://www.youtube.com/embed/xXCCGYqAWqI?si=iTiAVvLAQPreai4a');

        $stalefish->addTricksVideo($video);  
        $manager->persist($stalefish);

        $tricks[] = $stalefish;

        // Tail grab
        $tailGrab = new Tricks();
        $tailGrab->setTitle('Tail grab')
            ->setDescription('Saisie de la partie arrière de la planche, avec la main arrière.')
            ->setUser($users[array_rand($users)]);
            $slugify = new Slugify();
            $slug = $slugify->slugify('Tail grab');

        $tailGrab->setSlug($slug);
        $tricksImage = new TricksImage();
        $tricksImage->setImageName('tail grab/337505_400w_1000h.jpeg');
        $tailGrab->addTricksImage($tricksImage);

        $tricksImage2 = new TricksImage();
        $tricksImage2->setImageName('tail grab/6769020405_e6ddfb7bf5_b.jpg');
        $tailGrab->addTricksImage($tricksImage2);
            
        $video = new TricksVideo();
        $video->setVideoUrl('https://www.youtube.com/embed/YAElDqyD-3I?si=k7lXk1MgoMRcBJ4O');

        $tailGrab->addTricksVideo($video);  
        $manager->persist($tailGrab);

        $tricks[] = $tailGrab;

        // Japan air
        $japanAir = new Tricks();
        $japanAir->setTitle('Japan air')
            ->setDescription('Saisie de l\'avant de la planche, avec la main avant, du côté de la carre frontside.')
            ->setUser($users[array_rand($users)]);
            $slugify = new Slugify();
            $slug = $slugify->slugify('Japan air');

        $japanAir->setSlug($slug);
        $tricksImage = new TricksImage();
        $tricksImage->setImageName('japan air/170.jpg');
        $japanAir->addTricksImage($tricksImage);

        $tricksImage2 = new TricksImage();
        $tricksImage2->setImageName('japan air/467139965-sage-kotsenburg-competes-in-the-mens-snowboard_1.jpg.CROP.promo-mediumlarge.webp');
        $japanAir->addTricksImage($tricksImage2);
            
        $video = new TricksVideo();
        $video->setVideoUrl('https://www.youtube.com/embed/X_WhGuIY9Ak?si=ART60948of2sirYA');

        $japanAir->addTricksVideo($video);  
        $manager->persist($japanAir);

        $tricks[] = $japanAir;
        
        // Seat Belt
        $seatBelt = new Tricks();
        $seatBelt->setTitle('Seat Belt')
            ->setDescription('Saisie du carre frontside à l\'arrière avec la main avant.')
            ->setUser($users[array_rand($users)]);
            $slugify = new Slugify();
            $slug = $slugify->slugify('Seat Belt');

        $seatBelt->setSlug($slug);
        $tricksImage = new TricksImage();
        $tricksImage->setImageName('seat Belt/hqdefault.jpg');
        $seatBelt->addTricksImage($tricksImage);

        $tricksImage2 = new TricksImage();
        $tricksImage2->setImageName('seat Belt/Seatbelt.jpg');
        $seatBelt->addTricksImage($tricksImage2);
            
        $video = new TricksVideo();
        $video->setVideoUrl('https://www.youtube.com/embed/4vGEOYNGi_c?si=dKJTmacmrnSHXiNh');

        $seatBelt->addTricksVideo($video);  
        $manager->persist($seatBelt);

        $tricks[] = $seatBelt;

        // Truck driver
        $truckDriver = new Tricks();
        $truckDriver->setTitle('Truck driver')
            ->setDescription('Saisie du carre frontside à l\'arrière avec la main avant.')
            ->setUser($admin);
            $slugify = new Slugify();
            $slug = $slugify->slugify('Truck driver');

        $truckDriver->setSlug($slug);
        $tricksImage = new TricksImage();
        $tricksImage->setImageName('truck driver/truck driver.jpeg');
        $truckDriver->addTricksImage($tricksImage);

        $tricksImage2 = new TricksImage();
        $tricksImage2->setImageName('truck driver/Truckdriver.jpg');
        $truckDriver->addTricksImage($tricksImage2);
            
        $video = new TricksVideo();
        $video->setVideoUrl('https://www.youtube.com/embed/4vGEOYNGi_c?si=dKJTmacmrnSHXiNh');

        $truckDriver->addTricksVideo($video);  
        $manager->persist($truckDriver);

        $tricks[] = $truckDriver;

        // backside360
        $backside360 = new Tricks();
        $backside360->setTitle('360')
            ->setDescription('On désigne par le mot « rotation » uniquement des rotations horizontales, les rotations verticales sont des flips. Le principe est d\'effectuer une rotation horizontale pendant le saut, puis d\'attérir en position switch ou normal. La nomenclature se base sur le nombre de degrés de rotation effectués : ici 360, trois six pour un tour complet.')
            ->setUser($admin);
            $slugify = new Slugify();
            $slug = $slugify->slugify('360');

        $backside360->setSlug($slug);
        $tricksImage = new TricksImage();
        $tricksImage->setImageName('360/Your-First-Frontside-360s_720x (1).webp');
        $backside360->addTricksImage($tricksImage);

        $tricksImage2 = new TricksImage();
        $tricksImage2->setImageName('360/how-to-frontside-360-snowboard-800.jpg');
        $backside360->addTricksImage($tricksImage2);
            
        $video = new TricksVideo();
        $video->setVideoUrl('https://www.youtube.com/embed/4vGEOYNGi_c?si=dKJTmacmrnSHXiNh');

        $backside360->addTricksVideo($video);  
        $manager->persist($backside360);

        $tricks[] = $backside360;

        // Comments
        $comments = [];

        for ($i = 0; $i < 10; $i++) {
            $comment = new Comments();
            $comment->setContent($this->faker->realText())
                ->setIsApproved(mt_rand(0, 3) === 0 ? false : true)
                ->setAuthor($users[mt_rand(0, count($users) - 1)]);

            $tricksInstance = $tricks[mt_rand(0, count($tricks) - 1)];
            $comment->setTricks($tricksInstance);

            $manager->persist($comment);
            $comments[] = $comment;  // Ajoutez chaque commentaire à votre tableau $comments
        }

        $manager->flush();
    }

    
}
