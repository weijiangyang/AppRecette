<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/connexion', name: 'security_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $utils): Response
    {

        return $this->render('pages/security/login.html.twig', [
            'last_username' => $utils->getLastUsername(),
            'error' => $utils->getLastAuthenticationError()
        ]);
    }

    #[Route('/deconnexion', name: 'security_logout', methods: ['GET', 'POST'])]
    public function logout(): Response
    {
        // nothing to do 
    }
}
