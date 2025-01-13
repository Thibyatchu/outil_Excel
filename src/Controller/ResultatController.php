<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ResultatController extends AbstractController
{
    #[Route('/resultat', name: 'app_resultat')]
    public function index(): Response
    {
        return $this->render('resultat/index.html.twig', [
            'controller_name' => 'ResultatController',
        ]);
    }
}
