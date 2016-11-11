# Instabab

## Description

Le projet Instabab a été réalisé dans le cadre de la licence professionnelle *Conception/Intégration de Systèmes Internet/Intranet pour l'Entreprise* dispensée à l'IUT Nancy-Charlemagne. Il s'agit de proposer un mini-réseau social qui permette de partager des photos de Kebab prises par les utilisateurs.

## Installation

Pour procéder à l'installation du projet sur votre machine, il vous faut :
- créer une base de données et y crééer les tables décrites dans le fichier **schema_db.sql** (des données fictives y sont présentes)
- dans le fichier *config/setting.php*, passer *displayErrorDetails* à *false*
- dans le fichier *config/database.php*, modifier les valeurs pour pouvoir vous connecter à votre base de données
- placer le **Document Root* sur le répertoire *public/*

Une fois ces étapes effectuées, l'application est fonctionnelle et accessible via le navigateur.

## Fonctionnalités

Les fonctionnalités de base sont les suivantes :
- enregistrement et authentification d'un utilisateur ;
- publication de photos avec une description et un lieu ;
- ajout d'un nouveau lieu ;
- ajout de hashtags sur les publications (en utilisant la syntaxe '#tag') ;
- accès aux fiches tags, lieux, profil utilisateur, publication
- moteur de recherche par tag, lieu, utilisateur, publication
- système de notation (*J'aime*/*Je n'aime pas*) et moyenne sur 5
- commentaires sur les publications
- modification du profil utilisateur
- ...

## Utilisation

 