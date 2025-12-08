<?php
include 'Model.php';

class Voiture {
    private $marque;
    private $couleur;
    private $immatriculation;

    // La syntaxe ... = NULL signifie que l'argument est optionel
    // Si un argument optionnel n'est pas fourni,
    // alors il prend la valeur par défaut, NULL dans notre cas
    public function __construct($m = NULL, $c = NULL, $i = NULL) {
        if (!is_null($m) && !is_null($c) && !is_null($i)) {
            // Si aucun de $m, $c et $i sont nuls,
            // c'est forcement qu'on les a fournis
            // donc on retombe sur le constructeur à 3 arguments
            $this->setMarque($m);
            $this->setCouleur($c);
            $this->setImmatriculation($i);
        }
    }

    public function getMarque(): string {
        return $this->marque;
    }
    public function getCouleur(): string {
        return $this->couleur;
    }
    public function getImmatriculation(): string {
        return $this->immatriculation;
    }
    private function setMarque(string $marque): void {
        $this->marque = $marque;
    }
    private function setCouleur(string $couleur): void {
        $this->couleur = $couleur;
    }
    private function setImmatriculation(string $immatriculation): void {
        $this->immatriculation = $immatriculation;
    }

    public function afficher(): void {
        var_dump([
            $this->marque,
            $this->couleur,
            $this->immatriculation
        ]);
    }

    public static function getAllVoitures(): array {
        $rep = Model::$pdo->query("SELECT * FROM voiture");
        $rep->setFetchMode(PDO::FETCH_CLASS, 'Voiture');    
        return $rep->fetchAll();
    }

    public static function getVoitureByImmat($immat): ?Voiture {
        $sql = "SELECT * FROM voiture WHERE immatriculation=:immat";
        // Préparation de la requête
        $req_prep = Model::$pdo->prepare($sql);

        $values = array(
            "immat" => $immat,
            //nomdutag => valeur, ...
        );
        // On donne les valeurs et on exécute la requête     
        $req_prep->execute($values);

        // On récupère les résultats comme précédemment
        $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Voiture');
        $tab_voit = $req_prep->fetchAll();
        // Attention, si il n'y a pas de résultats, on renvoie false
        if (empty($tab_voit))
            return null;
        return $tab_voit[0];
    }

    public static function createVoiture($immat,$mq,$cl): bool {
        $sql = "INSERT INTO voiture VALUES (:immat, :mq, :cl)";
        $req_prep = Model::$pdo->prepare($sql);
        $values = array(
            "immat" => $immat,
            "mq" => $mq,
            "cl" => $cl
        );
        $req_prep->execute($values);
        // Retourne le nombre de lignes affectées par la requête
        // -> une si l'insert a fonctionné, 0 si ce n'est pas le cas
        return $req_prep->rowCount() > 0;
    }

    public static function updateVoitureByImmat($immat,$newImmat,$newMq,$newCl): bool {
        $sql = "UPDATE `voiture` SET `immatriculation`=:new_immat, `marque`=:new_mq, `couleur`=:new_cl WHERE `immatriculation`=:immat";
        $req_prep = Model::$pdo->prepare($sql);
        $values = array(
            "immat" => $immat,
            "new_immat" => $newImmat,
            "new_mq" => $newMq,
            "new_cl" => $newCl
        );
        $req_prep->execute($values);
        // Retourne le nombre de lignes affectées par la requête
        // -> on vérifie de la même façon la réussite de la requête
        return $req_prep->rowCount() > 0;
    }

    public static function deleteVoitureByImmat($immat): bool {
        $sql = "DELETE FROM `voiture` WHERE `immatriculation`=:immat";
        $req_prep = Model::$pdo->prepare($sql);
        $values = array(
            "immat" => $immat
        );
        $req_prep->execute($values);
        // Retourne le nombre de lignes affectées par la requête
        // -> on vérifie de la même façon la réussite de la requête
        return $req_prep->rowCount() > 0;
    }
}
