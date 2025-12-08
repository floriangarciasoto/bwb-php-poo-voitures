<?php
include 'Voiture.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['immatriculation'])) return;
    if (!isset($_GET['marque'])) return;
    if (!isset($_GET['couleur'])) return;

    $immatriculation = trim($_GET['immatriculation']);
    $marque = trim($_GET['marque']);
    $couleur = trim($_GET['couleur']);

    try {
        Voiture::createVoiture($immatriculation,$marque,$couleur);
        echo "Voiture ajoutée avec succès !";
    } catch (PDOException $e) {
        echo "Erreur lors de la création d'une voiture : " . $e->getMessage(); // affiche un message d'erreur
    }
}
