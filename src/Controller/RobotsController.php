<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RobotsController extends AbstractController
{
    #[Route('/robots.txt', name: 'app_robots')]
    public function index(): Response
    {
        return $this->render('robots.txt');
    }
}
