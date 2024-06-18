<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\MailService;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * This function permet a user  to connecter with the site
     *
     * @param AuthenticationUtils $utils
     * @return Response
     */
    #[Route('/connexion', name: 'security_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $utils): Response
    {
      
        return $this->render('pages/security/login.html.twig', [
            'last_username' => $utils->getLastUsername(),
            'error' => $utils->getLastAuthenticationError()
        ]);
    }

    
    
    /**
     * This function permet the current user to deconnect from the site 
     *
     * @return Response
     */
    #[Route('/deconnexion', name: 'security_logout', methods: ['GET', 'POST'])]
    public function logout(): Response
    {
        // nothing to do 
    }

    
    /**
     * This function permet un client register sur this site and a email sent to the mail of client once the registration is successful.
     *
     * @param MailService $mail
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/inscription', name: 'security_registration', methods: ['GET', 'POST'])]
    public function registration(MailService $mail,Request $request, EntityManagerInterface $em): Response
    {
        $user = new User;
        $user->setRoles(['ROLE_USER']);
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $user = $form->getData();
            $em->persist($user);
            $em->flush();
            // $mail->sendEmail(
            //     'admin@appRecette.com',
            //     'confirmation de l\'inscription',
            //     'pages/emails/inscription.html.twig',
            //     ['contact' => $form->getData()->getFullName()],
            //     $form->getData()->getEmail()
            // );    
            
            $this->addFlash(
                'success',
                'Votre compte a bien été registré'
            );
            return $this->redirectToRoute('security_login');
        }
        return $this->render('pages/security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
