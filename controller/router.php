<?php
require_once 'ControllerVoiture.php';

// On recupère l'action passée dans l'URL
$action = $_GET['action'];

// On exécute la méthode correspondante à l'action fournie par l'utilisateur uniquement si elle existe dans le contrôleur
// On vérifie la présence de la méthode dans l'instance créée par "new ControllerVoiture()"
if (method_exists(new ControllerVoiture(),$action)) {
    // Appel de la méthode statique $action de ControllerVoiture
    ControllerVoiture::$action();
}
