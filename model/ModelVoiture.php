<?php
include 'Model.php';

class ModelVoiture {
    private $marque;
    private $couleur;
    private $immatriculation;

    // La syntaxe ... = NULL signifie que l'argument est optionel
    // Si un argument optionnel n'est pas fourni,
    // alors il prend la valeur par défaut, NULL dans notre cas
    public function __construct($m = NULL, $c = NULL, $i = NULL) {
        if ($m !== null && $c !== null && $i !== null) {
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

    public static function getAllVoitures(): array {
        $rep = Model::$pdo->query("SELECT * FROM voiture");
        $rep->setFetchMode(PDO::FETCH_CLASS, 'ModelVoiture');    
        return $rep->fetchAll();
    }

    public static function getVoitureByImmat(string $immat): ?ModelVoiture {
        $sql = "SELECT * FROM voiture WHERE immatriculation=:immat";
        // Préparation de la requête
        $req_prep = Model::$pdo->prepare($sql);
        // Association des valeurs à remplir dans la requête
        $values = [
            "immat" => $immat,
            //nomdutag => valeur, ...
        ];
        // On donne les valeurs et on exécute la requête     
        $req_prep->execute($values);

        // On récupère les résultats comme précédemment
        $req_prep->setFetchMode(PDO::FETCH_CLASS, 'ModelVoiture');
        $tab_voit = $req_prep->fetchAll();
        // Attention, si il n'y a pas de résultats, on renvoie false
        if (empty($tab_voit))
            return null;
        return $tab_voit[0];
    }

    public function save(): bool {
        $sql = "INSERT INTO voiture VALUES (:immat, :mq, :cl)";
        $req_prep = Model::$pdo->prepare($sql);
        $values = [
            "immat" => $this->immatriculation,
            "mq" => $this->marque,
            "cl" => $this->couleur
        ];
        $req_prep->execute($values);
        // Retourne le nombre de lignes affectées par la requête
        // -> une si l'insert a fonctionné, 0 si ce n'est pas le cas
        return $req_prep->rowCount() > 0;
    }

    public static function createVoiture(string $immat,string $mq,string $cl): bool {
        // Instanciation d'une nouvelle voiture
        $nouvelleVoiture = new ModelVoiture($mq,$cl,$immat);
        // Retour du booléen retrourné par la méthode save()
        return $nouvelleVoiture->save();
    }

    public static function updateVoitureByImmat(string $immat,string $newImmat,string $newMq,string $newCl): bool {
        $sql = "UPDATE `voiture` SET `immatriculation`=:new_immat, `marque`=:new_mq, `couleur`=:new_cl WHERE `immatriculation`=:immat";
        $req_prep = Model::$pdo->prepare($sql);
        $values = [
            "immat" => $immat,
            "new_immat" => $newImmat,
            "new_mq" => $newMq,
            "new_cl" => $newCl
        ];
        $req_prep->execute($values);
        // Retourne le nombre de lignes affectées par la requête
        // -> on vérifie de la même façon la réussite de la requête
        return $req_prep->rowCount() > 0;
    }

    public static function deleteVoitureByImmat(string $immat): bool {
        $sql = "DELETE FROM `voiture` WHERE `immatriculation`=:immat";
        $req_prep = Model::$pdo->prepare($sql);
        $values = [
            "immat" => $immat
        ];
        $req_prep->execute($values);
        // Retourne le nombre de lignes affectées par la requête
        // -> on vérifie de la même façon la réussite de la requête
        return $req_prep->rowCount() > 0;
    }
}
