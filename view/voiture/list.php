<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Liste des voitures</title>
    </head>
    <body>
        <a href="/controller/router.php?action=create">Créer une voiture</a>
        <?php
        foreach ($tab_v as $v)
            echo '<p> Voiture d\'immatriculation ' . $v->getImmatriculation() . '. <a href="/controller/router.php?action=read&immat=' . $v->getImmatriculation() . '">Voir les détails</a> <a href="/controller/router.php?action=delete&immat=' . $v->getImmatriculation() . '">Supprimer</a></p>';
        ?>
    </body>
</html>