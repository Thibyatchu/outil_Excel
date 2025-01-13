<?php

// src/Controller/AccueilController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'accueil')]
    public function index(Request $request): Response
    {
        if ($request->isMethod('POST') && $request->files->has('file')) {
            // Récupérer le fichier
            $file = $request->files->get('file');
            $filePath = $file->getPathname();

            // Charger le fichier avec PhpSpreadsheet
            $spreadsheet = IOFactory::load($filePath);
            $data = [];

            // Extraire les données du fichier
            foreach ($spreadsheet->getActiveSheet()->getRowIterator() as $row) {
                $rowData = [];
                foreach ($row->getCellIterator() as $cell) {
                    $rowData[] = $cell->getValue() ?? '';  // Remplacer les null par des chaînes vides
                }
                $data[] = $rowData;
            }

            // Enregistrer les données dans la session pour les utiliser dans la page d'affichage
            $session = $request->getSession();
            $session->set('spreadsheet_data', $data);

            // Rediriger vers la page d'affichage
            return $this->redirectToRoute('affichage');
        }

        return $this->render('accueil/index.html.twig');
    }
}