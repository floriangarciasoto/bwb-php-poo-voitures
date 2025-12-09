# App PHP POO Voitures

Développement d'une application de gestion de voitures en POO avec une architecture MVC

## Initialisation

Démarrage du serveur :

```
PS C:\Users\fgs\code\bwb\m10-php\bwb-php-poo-voitures> php -S localhost:8000 -t .
[Mon Dec  8 12:13:39 2025] PHP 8.3.6 Development Server (http://localhost:8000) started
```

## Utilisation

Les différentes pages de l'application se situent aux URL suivantes :


| Page | URL |
|---|---|
| Liste des voitures | http://localhost:8000/controller/router.php?action=readAll |
| Détails d'une voiture | http://localhost:8000/controller/router.php?action=read&immat=IMMAT |
| Ajout d'une voiture | http://localhost:8000/controller/router.php?action=create |
| Suppression d'une voiture | http://localhost:8000/controller/router.php?action=delete&immat=IMMAT |
