<?php
include 'Voiture.php';

var_dump(Voiture::getAllVoitures());

var_dump(Voiture::getVoitureByImmat('98X.com'));
var_dump(Voiture::getVoitureByImmat('FC <hr>'));

echo "Création ...<br>\n";
Voiture::createVoiture("FORD.NET","Ford","Blueueueue");

echo "Après création :<br>\n";
var_dump(Voiture::getAllVoitures());
var_dump(Voiture::getVoitureByImmat('FORD.NET'));

echo "Mise à jour ...<br>\n";
Voiture::updateVoitureByImmat("FORD.NET", "FORD.COM","Fordu","Rouuuuge");

echo "Après mise à jour :<br>\n";
var_dump(Voiture::getVoitureByImmat('FORD.NET'));
var_dump(Voiture::getVoitureByImmat('FORD.COM'));

echo "Suppression ...<br>\n";
Voiture::deleteVoitureByImmat("FORD.COM");

echo "Après suppression :<br>\n";
var_dump(Voiture::getAllVoitures());
