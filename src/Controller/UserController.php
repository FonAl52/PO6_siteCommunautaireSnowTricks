<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use App\Form\ChangePasswordType;
use App\Repository\UserRepository;
use App\Service\Mailer\MailSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class UserController extends AbstractController
{
    /**
     * This controller allow us to edit user's profile
     *
     * @param User $choosenUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $hasher
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

        if ($user === $choosenUser) {
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
     * This controller allow us to show the forgot password form
     *
     * @param Request $request
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $manager
     * @param TokenGeneratorInterface $tokenGeneratorInterface
     * @param MailSender $mailSender
     * @return Response
     * 
     */
    #[Route('/mot-de-passe-oublie', 'forget.password', methods: ['GET', 'POST'])]


    public function resetPassword(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $manager,
        TokenGeneratorInterface $tokenGeneratorInterface,
        MailSender $mailSender
    ): Response {
        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneByEmail($form->get('email')->getData());

            if ($user) {
                $token = $tokenGeneratorInterface->generateToken();

                $user->setResetToken($token);

                $manager->persist($user);
                $manager->flush();

                $url = $this->generateUrl('change.password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                $subject = 'Snow Tricks 🏂 - Réinitialisation de mot de passe';
                $body = 'Voici votre <a href="' . $url . '">lien de réinitialisation</a>.';
                $mailSender->sendEmail($form, $subject, $body);
                //generate email
                $this->addFlash(
                    'success',
                    'Un mail de réinitialisation vous as été envoyer.'
                );
            } else {
                $this->addFlash(
                    'warning',
                    'Le mail renseigné est incorrect.'
                );
            }
        }

        return $this->render('security/forget_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * This controller allow us to edit user's password
     *
     * @param string $token
     * @param Request $request
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/changement-mot-de-passe/{token}', 'change.password', methods: ['GET', 'POST'])]


    public function editPassword(
        string $token,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $manager
    ): Response {
        $user = $userRepository->findOneByResetToken($token);

        if ($user) {
            $form = $this->createForm(ChangePasswordType::class);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $user->setUpdatedAt(new \DateTimeImmutable());
                $user->setPlainPassword(
                    $form->getData()['newPassword']
                );
                $user->setResetToken(NULL);

                $this->addFlash(
                    'success',
                    'Le mot de passe a été modifié.'
                );

                $manager->persist($user);
                $manager->flush();

                return $this->redirectToRoute('home.index');
            }
            return $this->render('security/change_password.html.twig', [
                'form' => $form->createView()
            ]);
        }
        $this->addFlash(
            'warning',
            'Jeton invalide.'
        );
        return $this->redirectToRoute('security.login');
    }

    /**
     * This controller allow us to edit user's password
     *
     * @param string $token
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/verification-mail/{token}', 'validate.email', methods: ['GET', 'POST'])]

    
    public function verifyMail(
        string $token,
        UserRepository $userRepository,
        EntityManagerInterface $manager
    ): Response {
        $user = $userRepository->findOneByResetToken($token);

        if ($user) {
            $user->setIsVerified(1);
            $user->setResetToken(NULL);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre compte est maintenant validé.'
            );
            return $this->redirectToRoute('security.login');
        }
        $this->addFlash(
            'warning',
            'Jeton invalide.'
        );
        return $this->redirectToRoute('security.login');
    }
}
