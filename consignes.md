# Partie 1

## 1 Création de la base de données
* Créez une table voiture possédant 3 champs :
- **immatriculation** de type VARCHAR et de longueur maximale 8, défini comme la clé primaire (Index : Primary)
- **marque** de type VARCHAR est de longueur maximale 25.
- **couleur** de type VARCHAR est de longueur maximale 12.

- InnoDB comme moteur de stockage, et utf8_general_ci

* Insérez des données en utilisant l’onglet insérer de PhpMyAdmin.

## 2 Création dun fichier de configuration bdd
* Créez un fichier **Conf.php**. Ce fichier contiendra une classe **Conf** possédant un attribut statique **$databases** 

```php
<?php
class Conf {
   
  static private $databases = array(
    
    // serveur 
    'hostname' => 'a_remplir',
   
    // Nom de la bdd
    'database' => 'a_remplir',
   
    // Utilisateur ('root' par défault)
    'login' => 'a_remplir',
    
    // Mot de passe
    'password' => 'a_remplir'
  );
   
  public static function getLogin() {

        return self::$databases['login'];
  }
   
}
?>
```
* Complétez **Conf.php** avec des méthodes statiques **getHostname()**, **getDatabase()** et **getPassword()**.

***Remarque : Notez qu’on appelle une méthode statique à partir du nom de la classe, en utilisant **::** au lieu de **->** en PHP.***

## 3 Connexion à la bdd avec l'objet PDO
* Créez un fichier **Model.php** déclarant une classe **Model**. Cette classe possédera :

- un attribut public static **$pdo**
- une fonction **public static function Init()**.

* Dans la fonction **Init**, initialiser l’attribut **$pdo** en lui assignant un objet PDO. 

```php
 new PDO("mysql:host=$hostname;dbname=$database_name",$login,$password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

```
* Stockez ce nouvel objet PDO dans la variable statique **self::$pdo**.

***Explication : Comme la variable est statique, elle s’accède par une syntaxe **Type::$nom_var** comme indiqué précédemment. Le type de l’objet courant s’obtient avec le mot clé **self**.***


* Le code précédent a besoin que les variables **$hostname**, **$database_name**, **$login** et **$password** contiennent les chaînes de caractères correspondant au serveur, au nom de la bdd, au login et au mot de passe. Créez donc ces variables avant le **new PDO** en récupérant les informations à l’aide des fonctions de la classe **Conf**.

* Comme notre classe Model dépend de Conf.php, ajoutez un **require_once 'Conf.php'** au début du fichier.

* Enfin, on souhaite que **Model** soit initialisée juste après sa déclaration. Appelez donc l’initialisation statique **Model::Init()** à la fin du fichier.
* Testons dès à présent notre nouvelle classe. Créez le fichier **testModel.php** suivant. Vérifiez que l’exécution de **testModel.php** ne donne pas de messages d’erreur.
```php
<?php
require_once "Model.php";
echo "Connexion réussie !" ;
?>
```
## 4 Gestion des erreurs de PDO
* Lorsqu’une erreur se produit, PDO lève une exception qu’il faut donc récupérer et traiter. Placez donc votre **new PDO(...)** au sein d’un **try - catch**.
```php
try{
  ... 
} catch(PDOException $e) {
  echo $e->getMessage(); // affiche un message d'erreur
  die();
}
```
* Pour avoir plus de messages d’erreur de PDO, activer le mode d'affichage des erreurs, et le lancement d'exception en cas d'erreur, en ajoutant dans la classe **Model** :
```php
self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
```

## 5 Opérations sur la base de données
* Les objets PDO servent à effectuer des requêtes **SQL**. Nous allons nous servir de deux méthodes fournies par PDO :
- La méthode **query($SQL_request)** de la classe PDO
    - prend en entrée une requête SQL (chaîne de caractères)
    - et renvoie la réponse de la requête dans une représentation interne pas immédiatement lisible (un objet **PDOStatement**).

- La méthode **fetchAll($fetch_style)**
de la classe **PDOStatement** s’appelle sur les réponses de requêtes et renvoie la réponse de la requête dans un format lisible par PHP. Plus précisément, elle renvoie un tableau d’entrée **SQL**, chaque entrée étant formattée comme un tableau ou un objet.

* Le choix du format se fait avec la variable $fetch_style. Les formats les plus communs sont :
- **PDO::FETCH_ASSOC** : Chaque entrée **SQL** est un tableau indexé par les noms des champs de la table de la BDD ;
- **PDO::FETCH_OBJ** : Chaque entrée SQL est un objet dont les noms d’attributs sont les noms des champs de la table de la BDD ;
- **PDO::FETCH_CLASS** : De même que PDO::FETCH_OBJ, chaque entrée SQL est un objet dont les noms d’attributs sont les noms des champs de la table de la BDD. Cependant, on peut dans ce cas spécifier le nom de la classe des objets. Pour ce faire, il faut avoir au préalable déclaré le nom de la classe avec la commmande suivante :
```php
$pdo_stmt->setFetchMode( PDO::FETCH_CLASS, 'class_name');
```
***Note : Plus précisément, PDO va dans l’ordre créer une instance de la classe demandée, écrire les attributs correspondants au champs de la BDD puis appeler le constructeur sans arguments.***

## Faire une requête SQL simple
Commençons par la requête SQL la plus simple, celle qui lit tous les éléments d’une table (voiture dans notre exemple) :
```sql
SELECT * FROM voiture
```
* Créez un fichier **lireVoiture.php** et y inclure le fichier contenant la classe **Model** pour pouvoir se connecter à la BDD. 
* Appelez la fonction **query** de l’objet PDO **Model::$pdo** en lui donnant la requête **SQL**. Stockez sa réponse dans une variable **$rep**.
* Comme expliqué précédemment, pour lire les réponses à des requêtes SQL, vous pouvez utiliser :
```php
$tab_obj = $rep->fetchAll(PDO::FETCH_OBJ)
```
qui renvoie un tableau d’objets **tab_obj** ayant pour attributs les champs de la BDD. Chacun des objets **$obj** de **$tab_obj** contient donc trois attributs **immatriculation**, **couleur** et **marque** (les champs de la BDD) qui sont accessibles classiquement par **$obj->immatriculation**, etc...
***Utilisez la fonction **fetchAll** pour afficher toutes les voitures. Servez-vous d’une boucle **foreach** pour itérer sur le tableau **$tab_obj**.**

* Nous allons mettre à jour le code de **lireVoiture.php** pour faire l’affichage à l’aide de la fonction **afficher()** de Voiture.
* Comme expliqué précédemment, vous pouvez récupérer directement un objet de la classe **Voiture** avec :-
```php
$rep->setFetchMode(PDO::FETCH_CLASS, 'Voiture');
$tab_voit = $rep->fetchAll();
```
* Incluez le fichier de la classe **Voiture** pour pouvoir l’utiliser;
* Avec l’option **PDO::FETCH_CLASS**, PDO va créer une instance de la classe **Voiture**, écrire les attributs correspondants au champs de la BDD puis appeler le constructeur sans arguments.
* Adaptons donc l’ancien constructeur de **Voiture** pour qu’il accepte aussi un appel sans arguments (en plus d’un appel avec trois arguments).
```php
// La syntaxe ... = NULL signifie que l'argument est optionel
// Si un argument optionnel n'est pas fourni,
//   alors il prend la valeur par défaut, NULL dans notre cas
public function __construct($m = NULL, $c = NULL, $i = NULL) {
  if (!is_null($m) && !is_null($c) && !is_null($i)) {
    // Si aucun de $m, $c et $i sont nuls,
    // c'est forcement qu'on les a fournis
    // donc on retombe sur le constructeur à 3 arguments
    $this->marque = $m;
    $this->couleur = $c;
    $this->immatriculation = $i;
  }
}
```
* Vous pouvez maintenant appeler **fetchAll** dans **lireVoiture.php** et faire l’affichage à l’aide de la méthode **afficher()**.
***Note : La variable **$tab_voit** contient un tableau d’objets de classe **Voiture**. Pour afficher les voitures, il faudra itérer sur le tableau avec une boucle **foreach**.***
* Nous allons maintenant isoler le code qui retourne toutes les voitures et en faire une méthode de Voiture.

* Créez une fonction statique **getAllVoitures()** dans la classe **Voiture** qui ne prend pas d’arguments et renvoie le tableau des voitures de la BDD.
* Mettez à jour **lireVoiture.php** pour appeler directement cette nouvelle fonction.

# Partie 2

## La sécurité / Les injections SQL
### Exemple d’injection SQL
* Imaginez un site Web qui, pour connecter un utilisateur, exécute la requête SQL suivante et accepte la connexion dès que la requête renvoie au moins une réponse.
````php
SELECT uid FROM Users WHERE name = '$nom' AND password = '$mot_de_passe';
````
* Un utilisateur malveillant pourrait taper les renseignements suivants :

- Utilisateur : **Dupont';--**
- Mot de passe : **n’importe lequel**
* La requête devient :
````sql
SELECT uid FROM Users WHERE name = 'Dupont'; -- ' AND password = 'mdp';
````
* ce qui est équivalent à :
````sql
SELECT uid FROM Users WHERE name = 'Dupont';
````
* L’attaquant peut alors se connecter sous l’utilisateur Dupont avec n’importe quel mot de passe. Cette attaque du site Web s’appelle une injection SQL.
[Source : ](https://fr.wikipedia.org/wiki/Injection_SQL)

## Les requêtes préparées
Imaginez que nous ayons codé une fonction **getVoitureByImmat($immatriculation)** comme suit :
```php
function getVoitureByImmat($immat) {
    $sql = "SELECT * from voiture WHERE immatriculation='$immat'"; 
    $rep = Model::$pdo->query($sql);
    $rep->setFetchMode(PDO::FETCH_CLASS, 'Voiture');
    return $rep->fetch();
}
```
*Cette fonction marche mais pose un gros problème de sécurité ; elle est vulnérable aux **injections SQL** et un utilisateur pourrait faire comme dans l’exemple précédent pour exécuter le code SQL qu’il souhaite.
* Pour empécher les **injections SQL**, nous allons utiliser une fonctionnalité qui s’appelle les **requêtes préparées** et qui est fournie par PDO. Voici comment les requêtes préparées fonctionnent :
- On met un tag *:nom_tag** en lieu de la valeur à remplacer dans la requête SQL
- On doit “préparer” la requête avec la commande **prepare($requete_sql)**
* Puis utiliser un tableau pour associer des valeurs aux noms des tags des variables à remplacer :
```php
$values = array("nom_tag" => "une valeur");
```
- Et exécuter la requête préparée avec **execute($values)**
- On peut alors récupérer les résultats comme précédemment avec **fetchAll()**
* Voici toutes ces étapes regroupées dans une fonction :
````php
function getVoitureByImmat($immat) {
    $sql = "SELECT * from voiture WHERE immatriculation=:nom_tag";
    // Préparation de la requête
    $req_prep = Model::$pdo->prepare($sql);

    $values = array(
        "nom_tag" => $immat,
        //nomdutag => valeur, ...
    );
    // On donne les valeurs et on exécute la requête	 
    $req_prep->execute($values);

    // On récupère les résultats comme précédemment
    $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Voiture');
    $tab_voit = $req_prep->fetchAll();
    // Attention, si il n'y a pas de résultats, on renvoie false
    if (empty($tab_voit))
        return false;
    return $tab_voit[0];
}
````
* Copiez la fonction précédente (**getVoitureByImmat($immat)**)dans la classe Voiture en la déclarant publique et statique.
***Remarque : Il existe une autre solution pour associer une à une les valeurs aux variables d’une requête préparée avec la fonction bindParam de la classe PDO (qui permet de donner le type de la valeur). Cependant nous vous conseillons d’utiliser systématiquement la syntaxe avec un tableau execute($values).***

* Désormais, toutes les requêtes SQL doivent être codées en utilisant des requêtes préparées, sauf éventuellement des requêtes SQL sans variable comme **SELECT * FROM voiture**.

* Créez une fonction **save()** dans la classe **Voiture** qui insère la voiture courante (**$this**) dans la BDD. On vous rappelle la syntaxe SQL d’une insertion :
````php
INSERT INTO table_name (column1, column2, ...) VALUES (value1, value2, ...)
````
***Attention : La requête INSERT INTO ne renvoie pas de résultats ; il ne faut donc pas faire de fetch() sous peine d’avoir une erreur **SQLSTATE[HY000]: General error** ***
* Testez cette fonction dans **lireVoiture.php** en créant un objet de classe **Voiture** et en l’enregistrant.
* Branchons maintenant notre enregistrement de voiture dans la BDD au formulaire de création de voiture du TD1 :

* Copiez dans le dossier les fichiers **creerVoiture.php** et **formulaireVoiture.html** 
* Modifier la page **creerVoiture.php** de sorte qu’elle sauvegarde l’objet **Voiture** reçu (en **POST**).

* Testez l’insertion grâce au formulaire **formulaireVoiture.html**.
````html
<form method="get" action="creerVoiture.php">
  <fieldset>
    <legend>Mon formulaire :</legend>
    <p>
      <label for="immat_id">Immatriculation</label> :
      <input type="text" placeholder="Ex : 256AB34" name="immatriculation" id="immat_id" required/>
    </p>
    <p>
      <input type="submit" value="Envoyer" />
    </p>
  </fieldset> 
</form>
````


============================================================

# Partie 3

## Architecture MVC : Modèle, Vue, Contrôleur
* Au fur et à mesure que votre application web grandit, vous allez rencontrer des difficultés à organiser votre code.Le modèle MVC est une bonne façon de la concevoir. On appelle cela un **design pattern**(patron de conception) une série de bonnes pratiques pour l’organisation de votre site.

## Présentation du design pattern MVC
* Le pattern **MVC** permet de bien organiser son code source. Jusqu’à présent, nous avons programmé de manière monolithique : nos pages Web mélangent traitement (PHP), accès aux données (SQL) et présentation (balises HTML). Nous allons maintenant séparer toutes ces parties pour plus de clarté.
* L’objectif de ce TD est donc de réorganiser le code du TD3 pour finalement y rajouter plus facilement de nouvelles fonctionnalités. Nous allons vous expliquer le fonctionnement sur l’exemple de la page lireVoiture.php du TD2 :
* L’architecture MVC est une manière de découper le code en trois bouts **Modèle**, **Vue** et **Contrôleur** ayant des fonctions bien précises. Le fichier **lireVoiture.php** va être réparti entre le contrôleur **controller/ControllerVoiture.php**, le modèle **model/ModelVoiture.php** et la vue **view/voiture/list.php**.

* Créez les répertoires **config**, **controller**, **model**, **view** et **view/voiture**.
renommez le fichier **Voiture.php** en **ModelVoiture.php**.
* Renommez la classe en **ModelVoiture**. Mettez en commentaire la fonction **afficher()** pour la désactiver.
* Pensez à corriger la classe appelée dans vos **setFetchMode()** pour créer des objets de **ModelVoiture**.
* Déplacez vos fichiers **ModelVoiture.php** et **Model.php** dans le répertoire **model/**.
* Déplacez **Conf.php** dans le dossier **config**.
* Corrigez le chemin relatif de l’include du fichier **Conf.php** dans **Model.php**.
***N.B. : Il est vraiment conseillé de renommer les fichiers et non de les copier. Avoir plusieurs copies de vos classes et fichiers est source d’erreur difficile à déboguer.***

* Voici l'arborescence des dossiers et fichiers à mettre en place:

Dossier **config**
    - fichier **Con.php**

Dossier **controller**
    - fichier **ControllerVoiture.php**
    - fichier **routeur.php**

Dossier **model**
    - fichier **Model.php**
    - fichier **ModelVoiture.php**

Dossier **view**
    - Dossier **voiture**
        - fichier **create.php**
        - fichier **detail.php**
        - fichier **error*.php**
        - fichier **list*.php**   


## Le modèle

* Le modèle est chargé de la gestion des données, notamment des interactions avec la base de données. ici, c'est la classe **Voiture**.

* Dans notre cas, la nouvelle classe ModelVoiture gère la persistance au travers des méthodes:
````php
 $mv->save();
 $mv2 = ModelVoiture::getVoitureByImmat($immatriculation);
 $arrayVoitures = ModelVoiture::getAllVoitures();
 ````
***N.B. : Souvenez-vous que les deux dernières fonctions **getVoitureByImmat** et **getAllVoitures** sont static. Elles ne dépendent donc que de leur classe (et non pas des objets instanciés). D’où la syntaxe différente **Class::fonction_statique()** pour les appeler.***

## La vue
* Dans la vue sont regroupés toutes les lignes de code qui génèrent la page HTML que l’on va envoyer à l’utilisateur. Les vues sont des fichiers qui ne contiennent quasiment exclusivement que du code HTML, à l’exception de quelques echo permettant d’afficher les variables pré-remplies par le contrôleur. Une boucle for est toutefois autorisée pour les vues qui affichent une liste d’éléments. La vue n’effectue pas de traitement, de calculs.
* Dans notre exemple, la vue serait le fichier **view/voiture/list.php** suivant. Le code de ce fichier permet d’afficher une page Web contenant toutes les voitures contenues dans la variable **$tab_v**.
* Créez la vue view/voiture/list.php avec le code suivant :
```php
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Liste des voitures</title>
    </head>
    <body>
        <?php
        foreach ($tab_v as $v)
            echo '<p> Voiture d\'immatriculation ' . $v->getImmatriculation() . '.</p>';
        ?>
    </body>
</html>
```
## Le contrôleur
* Le contrôleur gère la logique du code qui prend des décisions. C’est en quelque sorte l’intermédiaire entre le modèle et la vue : le contrôleur va demander au modèle les données, les analyser, prendre des décisions et appelle la vue adéquate en lui donnant le texte à afficher à la vue. Le contrôleur contient exclusivement du PHP.

* Il existe une multitude d’implémentations du MVC:
- un gros contrôleur unique
- un contrôleur par modèle
- un contrôleur pour chaque action de chaque modèle

* Nous choisissons ici la version intermédiaire et commençons à créer un contrôleur pour **ModelVoiture**. Voici le contrôleur **controller/ControllerVoiture.php** sur notre exemple :
````php
<?php
require_once ('../model/ModelVoiture.php'); // chargement du modèle
$tab_v = ModelVoiture::getAllVoitures();     //appel au modèle pour gerer la BD
require ('../view/voiture/list.php');  //redirige vers la vue
?>
````
* Notre contrôleur se décompose donc en plusieurs parties :
- On charge la déclaration de la classe ModelVoiture ;
- on se sert du modèle pour récupérer le tableau de toutes les voitures avec
- $tab_v = Voiture::getAllVoitures();
- on appelle alors la vue qui va nous générer la page Web avec
- require ('../view/voiture/list.php');

***Pourquoi **../** ? Les adresses sont relatives au fichier courant qui est controller/ControllerVoiture.php dans notre cas. Notez bien que c’est le contrôleur qui initialise la variable **$tab_v** et que la vue ne fait que **lire** cette variable pour générer la page Web.***
* Créez le contrôleur **controller/ControllerVoiture.php** avec le code précédent.
* Testez votre page en appelant l’URL **…/controller/ControllerVoiture.php**

## Le routeur : un autre composant du contrôleur
* Un contrôleur doit en fait gérer plusieurs pages. Dans notre exemple, il doit gérer toutes les pages liées au modèle ModelVoiture. Du coup, on regroupe le code de chaque page Web dans une fonction, et on met le tout dans une classe contrôleur.
* Voici à quoi va ressembler notre contrôleur **ControllerVoiture.php**. On reconnaît dans la fonction **readAll** le code précédent qui affiche toutes les voitures.
````php
<?php
require_once ('../model/ModelVoiture.php'); // chargement du modèle
class ControllerVoiture {
    public static function readAll() {
        $tab_v = ModelVoiture::getAllVoitures();     //appel au modèle pour gerer la BD
        require ('../view/voiture/list.php');  //"redirige" vers la vue
    }
}
?>
````
* On appelle **action** une fonction du contrôleur ; une action correspond généralement à une page Web. Dans notre exemple du contrôleur **ControllerVoiture**, nous allons bientôt rajouter les actions qui correspondent aux pages suivantes :
- afficher toutes les voitures : action readAll
- afficher les détails d’une voiture : action read
- afficher le formulaire de création d’une voiture : action create
- créer une voiture dans la BDD et afficher un message de confirmation : action created
- supprimer une voiture et afficher un message de confirmation : action delete

* Pour recréer la page précédente, il manque encore un bout de code qui appelle la méthode **ControllerVoiture::readAll()**. Le routeur est la partie du contrôleur qui s’occupe d’appeler l’action du contrôleur. Un routeur simpliste serait le fichier suivant **controller/routeur.php** :
````php
<?php
require_once 'ControllerVoiture.php';
ControllerVoiture::readAll(); // Appel de la méthode statique $action de ControllerVoiture
?>
````
* Modifiez le code de **ControllerVoiture.php** et créez le fichier **controller/routeur.php** pour correspondre au code ci-dessus ;
* Testez la nouvelle architecture en appelant la page **…/controller/routeur.php**.
## Maintenant un vrai routeur
* Le code précédent marche sauf que le client doit pouvoir choisir quelle action est-ce qu’il veut effectuer. Du coup, il va faire une requête pour la page **routeur.php** mais en envoyant l’information qu’il veut que action soit égal à **readAll**. Pour transmettre ces données à la page du routeur, nous allons les écrire dans l’URL avec la syntaxe du **query string** [cf. sur query string](https://en.wikipedia.org/wiki/Query_string).

![Query string](https://howto.caspio.com/wp-content/uploads/2021/04/creating_query_strings1.png)

* De son côté, le routeur doit récupérer l’action envoyée et appeler la méthode correspondante du contrôleur. Pour appeler la méthode statique de **ControllerVoiture** dont le nom se trouve dans la variable **$action**, le PHP peut faire comme suit. Voici le fichier **controller/routeur.php** mis à jour :
````php
<?php
require_once 'ControllerVoiture.php';
// On recupère l'action passée dans l'URL
$action = ...;
// Appel de la méthode statique $action de ControllerVoiture
ControllerVoiture::$action(); 
?>
````
* Modifiez le code **controller/routeur.php** pour correspondre au code ci-dessus en remplissant vous-même la **ligne 4**.
* Testez la nouvelle architecture en appelant la page **…/controller/routeur.php** en rajoutant l’information que **action** est égal à **readAll** dans l’URL au format **query string**.
* 

* Voici le déroulé de l’exécution du routeur pour l’action **readAll**:
- Le client demande l’URL …/controller/routeur.php?action=readAll.
- Le routeur récupère l’action donnée par l’utilisateur dans l’URL avec $action = $_GET['action']; (donc $action="readAll")
- le routeur appelle la méthode statique readAll de ControllerVoiture.php
- ControllerVoiture.php se sert du modèle pour récupérer le tableau de toutes les voitures ;
- ControllerVoiture.php appelle alors la vue qui va nous générer la page Web.

## Vue "détail d’une voiture"
* Comme la page qui liste toutes les voitures (**action readAll**) ne donne pas toutes les informations, nous souhaitons créer une page de **détail** dont le rôle sera d’afficher toutes les informations de la voiture. Cette action aura besoin de connaître l’immatriculation de la voiture visée ; on utilisera encore le **query string** pour passer l’information dans l’URL en même temps que l’action :
**…/routeur.php?action=read&immat=AAA111BB**
* Créez une vue **./view/voiture/detail.php** qui doit afficher tous les détails de la voiture stockée dans **$v** de la même manière que l’ancienne fonction **afficher()** (encore commentée dans **ModelVoiture**).
***Note : La variable **$v** sera initialisée dans le contrôleur plus tard, cf. **$tab_v** dans l’exemple précédent.***
* Rajoutez une action read au contrôleur **ControllerVoiture.php**. Cette action devra récupérer l’immatriculation donnée dans l’URL, appeler la fonction **getVoitureByImmat()** du modèle, mettre la voiture visée dans la variable **$v** et appeler la vue précédente.
* Testez cette vue en appelant la page du routeur avec les bons paramètres dans l’URL.
* Rajoutez des liens cliquables sur les immatriculations de la vue **list.php** qui renvoient sur la vue de détail de la voiture concernée.
* On souhaite gérer les immatriculations non reconnues: Créez un vue **./view/voiture/error.php** qui affiche un message d’erreur et renvoyez vers cette vue si **getVoitureByImmat()** ne trouve pas de voiture qui correspond à cette immatriculation.
## Vue “ajout d’une voiture”
* Commençons par l’action create qui affichera le formulaire :
- Créez la vue **./view/voiture/create.php** qui reprend le code de **formulaireVoiture.html** fait dans la partie 1.
- La page de traitement de ce formulaire devra être **l’action created** du routeur **routeur.php**.
- Rajoutez une action **create** à **ControllerVoiture.php** qui affiche cette vue.
* Testez votre page en appelant l’action **create** de **routeur.php**.
* Créez l’action **created** dans le contrôleur qui devra :
- récupérer les donnés de la voiture à partir de la **query string**,
- créer une instance de **ModelVoiture** avec les données reçues,
- appeler la méthode save du modèle,
- appeler la fonction **readAll()** pour afficher le tableau de toutes les voitures.
* Testez l’action created de **routeur.php** en donnant l’immatriculation, la marque et la couleur dans l’URL.
* Testez le tout, c-à-d. que la création de la voiture depuis le formulaire (action **create**) appelle bien l’action created et que la voiture est bien créée dans la BDD.
* Attention à l’envoi de **action=created** : Vous souhaitez envoyer l’information **action=created** en plus des informations saisies lors de l’envoi du formulaire. Il y a deux possibilités :
- Vous pouvez rajouter l’information dans l’URL avec :
````html
<form action='routeur.php?action=created' ...>
````
* Ou vous rajoutez un champ caché à votre formulaire :
````html
<input type='hidden' name='action' value='created'>
````
## Bonus
* Utilisons les **try / catch** sur le requêtes SQL pour traiter l’erreur qui se produit quand on veut sauvegarder une voiture déjà existante :
- Créez une telle erreur
- Utilisez un affichage de débogage pour explorer le contenu de l’objet d’erreur **PDOException** **$e** dans **save()** ;
- Identifiez le code d’erreur MySql qui correspond à notre erreur. Utilisez si nécessaire la page MySql des codes d’erreurs.
- Si cette erreur se produit, faites que **save()** renvoie **false** plutôt que de planter.
- Traiter le cas où **save()** renvoie **false** dans l’action **created** pour renvoyer vers une page d’erreur.
* Rajouter une fonctionnalité “**Supprimer une voiture**” à votre site (action **delete**). Ajouter un lien cliquable pour supprimer chaque voiture dans la liste des voitures (dans la vue **view/voiture/list.php**).

