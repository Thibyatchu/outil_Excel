<?php

// src/Controller/AffichageController.php
namespace App\Controller;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AffichageController extends AbstractController
{
    #[Route('/affichage', name: 'affichage')]
    public function index(Request $request): Response
    {
        // Initialisation des variables
        $message = 'Aucun fichier reçu';
        $data = null;

        // Vérifier si un fichier a bien été envoyé
        if ($request->isMethod('POST') && $request->files->get('file')) {
            $file = $request->files->get('file');

            try {
                // Charger le fichier avec PhpSpreadsheet
                $spreadsheet = IOFactory::load($file->getPathname());
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray();  // Convertir les données du fichier en tableau PHP
                $message = 'Fichier traité avec succès';
            } catch (\Exception $e) {
                // En cas d'erreur de traitement du fichier
                $message = 'Erreur lors du traitement du fichier: ' . $e->getMessage();
            }
        }

        // Passer les données à la vue
        return $this->render('affichage/index.html.twig', [
            'message' => $message,
            'data' => $data,
        ]);
    }
}
