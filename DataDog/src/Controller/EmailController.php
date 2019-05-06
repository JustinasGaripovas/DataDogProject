<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class EmailController extends AbstractController
{

    /**
     * @Route("/resetPassword/{token}", name="create_password")
     */
    public function createPasswordFromReset(Request $request, UserPasswordEncoderInterface $encoder)
    {

        $reset_token = $routeParams = $request->attributes->get('token');;
        $em = $this->getDoctrine()->getManager();
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->findByResetToken($reset_token);

        $defaultData = [];
        $form = $this->createFormBuilder($defaultData)

            ->add('password', RepeatedType::class, array(
                'first_options'  => ['label' => 'Naujas slaptažodis'],
                'second_options' => ['label' => 'Pakartoti naują slaptažodį'],
                'type' => PasswordType::class,
                'invalid_message' => 'Nesutinka slaptažodžiai',
                'options' => array(
                    'attr' => array(
                        'class' => 'password-field'
                    )
                ),
                'required' => true,
                'mapped' => false
            ))
            ->add('Update password', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys

            $passwordEncoder = $encoder;


            $user->setResetToken(null);
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $em->flush();

            return $this->redirectToRoute('event_index');

        }


        return $this->render('email/create.html.twig', [
            'controller_name' => $reset_token,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/resetPassword", name="reset_form")
     */
    public function resetRequest(Request $request, \Swift_Mailer $mailer)
    {


        $defaultData = [];
        $form = $this->createFormBuilder($defaultData)
            ->add('email', EmailType::class)
            ->add('Reset password', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $this->initializePasswordReset($data['email'],$mailer);

            return $this->redirectToRoute('event_index');
        }

        return $this->render('email/reset.html.twig', [
            'controller_name' => 'EmailController',
            'form' => $form->createView()
        ]);
    }


    public function initializePasswordReset($email, \Swift_Mailer $mailer)
    {
        $reset_token = $this->generateRandomString();

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->findByEmail($email);

        if (!$user) {
            return $this->redirectToRoute('event_index');
        }

        $user->setResetToken($reset_token);
        $entityManager->flush();


        $message = (new \Swift_Message('Password reset request'))
            ->setFrom('datadog.ktu@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'email/reset_confirmation_email.html.twig',
                    ['email' => $email, 'reset_token' => $reset_token]

                ),
                'text/html'
            )

        ;

        $mailer->send($message);

    }

    function generateRandomString($length = 15) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }




}
