<?php
require_once ('../model/ModelVoiture.php'); // chargement du modèle
class ControllerVoiture {
    public static function readAll() {
        $tab_v = ModelVoiture::getAllVoitures();     //appel au modèle pour gerer la BD
        require ('../view/voiture/list.php');  //"redirige" vers la vue
    }

    public static function read() {
        $immat = trim($_GET['immat']);
        $v = ModelVoiture::getVoitureByImmat($immat);     //appel au modèle pour gerer la BD
        require ('../view/voiture/detail.php');  //"redirige" vers la vue
    }

    public static function create() {
        require ('../view/voiture/create.php');  //"redirige" vers la vue
    }

    public static function created() {
        if (!isset($_GET['immatriculation'])) return;
        if (!isset($_GET['marque'])) return;
        if (!isset($_GET['couleur'])) return;

        $immatriculation = trim($_GET['immatriculation']);
        $marque = trim($_GET['marque']);
        $couleur = trim($_GET['couleur']);

        try {
            ModelVoiture::createVoiture($immatriculation,$marque,$couleur);
            header('Location: /controller/router.php?action=readAll');
        } catch (PDOException $e) {
            $errorMessage = "Erreur lors de la création d'une voiture : ";
            if ($e->getCode() === '23000') $errorMessage .= "une voiture avec la même plaque ($immatriculation) existe déjà.";
            else $errorMessage .= $e->getMessage();
            require ('../view/voiture/error.php');  //"redirige" vers la vue
        }
    }

    public static function delete() {
        if (!isset($_GET['immat'])) return;

        $immatriculation = trim($_GET['immat']);

        try {
            ModelVoiture::deleteVoitureByImmat($immatriculation);
            header('Location: /controller/router.php?action=readAll');
        } catch (PDOException $e) {
            $errorMessage = "Erreur lors de la suppression d'une voiture : " . $e->getMessage();
            require ('../view/voiture/error.php');  //"redirige" vers la vue
        }
    }
}
