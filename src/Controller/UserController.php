<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class UserController extends AbstractController
{
    /**
     * This controller allow us to edit user's profile
     *
     * @param User $choosenUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/utilisateur/edition/{id}', name: 'user.edit', methods: ['GET', 'POST'])]
    public function edit(
        User $choosenUser,
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher,
        Security $security
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $security->getUser();
        
        if ($user === $choosenUser){
            $form = $this->createForm(UserType::class, $choosenUser);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($hasher->isPasswordValid($choosenUser, $form->getData()->getPlainPassword())) {
                    $user = $form->getData();
                    
                    $manager->persist($user);
                    $manager->flush();

                    $this->addFlash(
                        'success',
                        'Les informations de votre compte ont bien été modifiées.'
                    );
                } else {
                    $this->addFlash(
                        'warning',
                        'Le mot de passe renseigné est incorrect.'
                    );
                }
            }

            return $this->render('user/edit.html.twig', [
                'form' => $form->createView(),
                'choosenUser' => $choosenUser
            ]);
        }
        return $this->redirectToRoute('home.index');
    }

    /**
     * This controller allow us to edit user's password
     *
     * @param User $choosenUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[Route('/utilisateur/edition-mot-de-passe/{id}', 'user.edit.password', methods: ['GET', 'POST'])]
    public function editPassword(
        User $choosenUser,
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher,
        Security $security
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $security->getUser();
        
        if ($user === $choosenUser){
            $form = $this->createForm(UserPasswordType::class);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($hasher->isPasswordValid($choosenUser, $form->getData()['plainPassword'])) {
                    $choosenUser->setUpdatedAt(new \DateTimeImmutable());
                    $choosenUser->setPlainPassword(
                        $form->getData()['newPassword']
                    );

                    $this->addFlash(
                        'success',
                        'Le mot de passe a été modifié.'
                    );

                    $manager->persist($choosenUser);
                    $manager->flush();

                    return $this->redirectToRoute('recipe.index');
                } else {
                    $this->addFlash(
                        'warning',
                        'Le mot de passe renseigné est incorrect.'
                    );
                }
            }

            return $this->render('user/edit_password.html.twig', [
                'form' => $form->createView()
            ]);
        }
        return $this->redirectToRoute('home.index');
    }
}

