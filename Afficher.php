<?php

// Inclure la classe Stock et gérer le traitement du formulaire
include_once "ClasseGestionstock.php";

if ($_SERVER && isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Créer une instance de Stock avec les données envoyées en POST
        $Info = new Stock($_POST);

        // Ajouter les données dans la base de données
        if (isset($_POST['Valide'])) {
            $Info->AjouterBase();
            $Info->Afficher();
            // Appeler la méthode Afficher peut récupérer les données si nécessaire
            header("Location:" . "Page1.php");
            // Rediriger vers la page principale pour réinitialiser le formulaire
            exit();
        } else if (isset($_POST['Modifier'])) {
            $Info->setID($_POST['id']);
            $Info->ModifierBase();
            $Info->Afficher();
            header("Location:" . "Page1.php");
            exit();
        }else if (isset($_POST['Supprimer'])) {
            $Info->setID($_POST['id']);
            $Info->SupprimerBase();
            $Info->Afficher();
            header("Location:" . "Page1.php");
            exit();
        }
    } catch (Exception $e) {
        // Gérer les exceptions et afficher un message d'erreur
        http_response_code(500);
        error_log("Erreur lors de l'ajout des données : " . $e->getMessage());
    }
} else {
    die("Méthode de requête invalide. Veuillez utiliser POST.");
}
