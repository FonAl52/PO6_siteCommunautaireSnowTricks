<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\ChangePasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use App\Repository\UserRepository;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



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
     * This controller allow us to show the forgot password form
     *
     * @param Request $request
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $manager
     * @param TokenGeneratorInterface $tokenGeneratorInterface
     * @return Response
     * 
     */
    #[Route('/mot-de-passe-oublie', 'forget.password', methods: ['GET', 'POST'])]
    public function resetPassword(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $manager,
        TokenGeneratorInterface $tokenGeneratorInterface,
        
    ): Response {
        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneByEmail($form->get('email')->getData());

            if($user){
                //generate reinitialisation token 
                $token = $tokenGeneratorInterface->generateToken();

                $user->setResetToken($token);

                $manager->persist($user);
                $manager->flush();

                //generate reinitialisation link
                $url = $this->generateUrl('change.password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                //generate email
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username = 'vod52m@gmail.com';                    //SMTP username
                    $mail->Password = 'kkmq ojuq hjpk gvdh';                               //SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                    $mail->Port = 465;                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                
                    //Recipients
                    $mail->setFrom('vod52m@gmail.com', 'Snow Tricks 🏂');
                    $userEmail = $form->get('email')->getData();
                    $mail->addAddress($userEmail);     //Add a recipient
                
                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'Snow Tricks 🏂 - Réinitialisation de mot de passe';
                    $mail->Body = 'Voici votre url de réinitialisation : ' . $url;
                    
                    $mail->send();
                    echo 'Message has been sent';
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
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
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[Route('/changement-mot-de-passe/{token}', 'change.password', methods: ['GET', 'POST'])]
    public function editPassword(
        string $token,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher,
        Security $security
    ): Response {
        $user = $userRepository->findOneByResetToken($token);

        if($user){
            $form = $this->createForm(ChangePasswordType::class);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $user->setUpdatedAt(new \DateTimeImmutable());
                $user->setPlainPassword(
                    $form->getData()['newPassword']
                );
    
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

}

