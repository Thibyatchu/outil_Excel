<?php

// src/Controller/AffichageController.php

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

        // Si l'utilisateur souhaite ignorer la première ou les deux premières lignes
        if ($ignoreFirstRows) {
            // Vérifier combien de lignes ignorer : soit 1, soit 2
            // Ignorer 1 ligne : commencer à partir de la deuxième ligne
            // Ignorer 2 lignes : commencer à partir de la troisième ligne
            $data = array_slice($data, 2, 5); // Ignore les deux premières lignes et affiche les 5 suivantes
        } else {
            // Sinon, afficher les 5 premières lignes
            $data = array_slice($data, 0, 5);
        }

        // Trouver le nombre de colonnes maximum
        $maxColumns = max(array_map('count', $data));

        // Compléter les lignes manquantes de colonnes avec des chaînes vides
        foreach ($data as &$row) {
            $row = array_pad($row, $maxColumns, '');
        }

        return $this->render('affichage/index.html.twig', [
            'data' => $data,
            'ignore_first_rows' => $ignoreFirstRows,
        ]);
    }
}

