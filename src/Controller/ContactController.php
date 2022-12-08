<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\MailService;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact_index')]
    public function index( MailService $mail,EntityManagerInterface $em, Request $request): Response
    {
        $contact = new Contact;

        if ($this->getUser()) {
            /**
             * @var User
             */
            $user = $this->getUser();
            $contact->setFullName($user->getFullName())
                ->setEmail($user->getEmail());
        }
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $em->persist($contact);
            $em->flush();
            $this->addFlash(
                'success',
                'Votre message a bien été envoyé'
            );
            //Email
            // $mail->sendEmail(
            //     $user->getEmail(),
            //     $contact->getSubject(),
            //     'pages/email/contact.html.twig',
            //     ['contact' => $contact]
            // );
            $mail->sendEmail(
                $form->getData()->getEmail(),
                $contact->getSubject(),
                'pages/emails/contact.html.twig',
                ['contact' => $contact]
            );
           
           

         
            return $this->redirectToRoute('app_index');
        }
        return $this->render('pages/contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

