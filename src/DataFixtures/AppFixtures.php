<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Group;
use App\Entity\TricksImage;
use App\Entity\Tricks;
use Faker\Generator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Component\HttpFoundation\File\File;

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

        // TricksImage
        $tricksImages = [];

        for ($i = 0; $i < 10; $i++) {
            $tricksImage = new TricksImage();
            $tricksImage->setName($this->faker->word());

            $imagePath = 'public/images/src_site/pexels-john-robertnicoud-38242.jpg';
            // Assuming you have a method to set the image path
            $tricksImage->setImageName($imagePath);

            $manager->persist($tricksImage);

            $tricksImages[] = $tricksImage;
        }

        // Tricks
        $tricks = [];

        for ($i = 0; $i < 10; $i++) {
            $tricks = new Tricks();
            $tricks->setTitle($this->faker->title())
                ->setDescription($this->faker->text(300))
                ->setUser($users[mt_rand(0, count($users) - 1)])
                ->setSlug('test');

            for ($k = 0; $k < mt_rand(5, 15); $k++) {
                $tricks->addTricksImage($tricksImages[mt_rand(0, count($tricksImages) - 1)]);
            }

            $manager->persist($tricks);
        }

        $manager->flush();
    }

    
}
