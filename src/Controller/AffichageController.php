<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AffichageController extends AbstractController
{
    #[Route('/affichage', name: 'affichage')]
    public function index(Request $request): Response
    {
        // Récupérer les données de la session
        $session = $request->getSession();
        $data = $session->get('spreadsheet_data', []);

        // Récupérer la valeur du checkbox "ignorer les premières lignes"
        $ignoreFirstRows = $request->get('ignore_first_rows', 'no') === 'yes';

        // Calculer quelles lignes afficher en fonction du choix de l'utilisateur
        if ($ignoreFirstRows) {
            $data = array_slice($data, 2, 5); // Ignorer les deux premières lignes et afficher les 5 suivantes
        } else {
            $data = array_slice($data, 0, 5); // Afficher les 5 premières lignes
        }

        // Trouver le nombre de colonnes maximum
        $maxColumns = max(array_map('count', $data));

        // Compléter les lignes manquantes de colonnes avec des chaînes vides
        foreach ($data as &$row) {
            $row = array_pad($row, $maxColumns, '');
        }

        // Préparer les options pour les listes déroulantes
        $columnNames = [];
        foreach (range(1, $maxColumns) as $i) {
            $columnNames[] = "Colonne $i";
        }

        return $this->render('affichage/index.html.twig', [
            'data' => $data,
            'ignore_first_rows' => $ignoreFirstRows,
            'columnNames' => $columnNames,
        ]);
    }
}
