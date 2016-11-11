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

Vous trouverez dans cette partie une description succinte du fonctionnement des fonctionnalités principales.

### Enregistrement d'un Utilisateur

Pour vous enregistrer en tant que nouvel utilisateur, il vous faut :
1. Cliquer sur le bouton **Inscription** en haut à droite de la page
2. Dans la fenêtre apparue à l'écran, compléter l'intégralité des champs. L'adresse mail doit être valide, unique et les deux mots de passe doivent être identiques
3. Valider le formulaire en cliquant sur le bouton **Inscription**
4. Si tous les champs ont été correctement complétés, votre compte utilisateur est créé. Dans le cas contraire, reportez-vous au message d'erreur affiché pour remplir correctement le formulaire.

### Authentification d'un Utilisateur

Pour vous authentifier en tant qu'utilisateur déjà enregistré, il suffit de :
1. Cliquer sur le bouton **Connexion** en haut à droite de la page
2. Dans la fenêtre apparue à l'écran, compléter les champs email et mot de passe à partir des informations fournies lors de votre inscription. Si vous n'avez pas encore créé votre compte utilisateur, référez-vous à la liste précédente
3. Valider le formulaire en cliquant sur le bouton **Connexion**
4. Si tous les champs ont été correctement complétés, votre compte utilisateur a été trouvé et une session a été créée. Dans le cas contraire, reportez-vous au message d'erreur affiché pour remplir correctement le formulaire.

### Publier une photo avec une description et un lieu

Pour publier une photo avec une description et un lieu, il est nécessaire de suivre les étapes suivantes :
1. Être connecté sur le site avec votre compte utilisateur. Si vous n'êtes pas connecté, référez-vous à la liste précédente.
2. Cliquer sur le bouton **Partager une Photo** en haut à droite de la page
3. Dans la fenêtre apparue à l'écran, compléter le champ *Description* (il est possible d'ajouter un ou plusieurs tags en utilisant la syntaxe **#tag**) et choisir une photo sur votre ordinateur en cliquant sur l'icône *upload*.
4. Concernant le lieu, deux cas de figure s'offrent à vous :
    - Le lieu existe déjà : le sélectionner dans la liste déroulante
    - Le lieu n'existe pas encore : sélectionner **Autre** dans la liste déoroulante et compléter les champs *Nom du Restaurant* et *Adresse du Restaurant* apparus à l'écran
5. Valider le formulaire en cliquant sur le bouton **Envoyer**
6. Si tous les champs ont été correctement complétés, votre photo est publiée. Dans le cas contraire, reportez-vous au message d'erreur affiché pour remplir correctement le formulaire.

### Afficher la liste des derniers tags utilisés, des lieux, des dernières publications ou votre profil

Pour afficher les derniers tags utilisés, la liste des lieux, votre profil ou les dernières publications, il faut :
1. Être connecté sur le site avec votre compte utilisateur. Si vous n'êtes pas connecté, référez-vous aux instructions ci-dessus.
2. Choisir l'onglet approprié dans le menu affiché sur la gauche de la page

### Afficher une publication dans sa vue détaillée

Pour afficher une publication dans sa vue détaillée (avec commentaires, note moyenne, photo grande taille,...), il suffit de :
1. Cliquer sur la photo de la publication
2. La publication est affichée totalement

### Afficher un profil, un tag ou un lieu

Pour afficher un profil utilisateur, une fiche de tag, une fiche de lieu, il faut :
1. Être connecté sur le site avec votre compte utilisateur. Si vous n'êtes pas connecté, référez-vous aux instructions ci-dessus.
2. Cliquer sur le nom d'utilisateur, sur le tag ou le nom du restaurant auquel on souhaite accéder
3. La page souhaitée s'affiche

### Effectuer une recherche

Pour effectuer une recherche, il suffit de :
1. Cliquer sur la barre de recherche située en haut de la page
2. Taper les mots clés à rechercher
3. Valider avec la touche **Entrée** ou en cliquant sur l'icône *Loupe*
4. Parcourir les résultats et accéder à la page souhaitée

### Noter une publication

Pour noter une publication, il faut :
1. Être connecté sur le site avec votre compte utilisateur. Si vous n'êtes pas connecté, référez-vous aux instructions ci-dessus.
2. Cliquer sur le bouton **J'aime** ou **Je n'aime pas** au choix
3. Votre note est prise en compte et impacte la moyenne (sur 5) donnée à une publication

### Commenter une publication

Pour commenter une publication, il est nécessaire de suivre les étapes suivantes :
1. Être connecté sur le site avec votre compte utilisateur. Si vous n'êtes pas connecté, référez-vous aux instructions ci-dessus.
2. Cliquer sur la photo de la publication de sorte d'afficher la vue détaillée 
3. Descendre jusqu'en bas du fil de commentaires pour faire apparaitre le formulaire à compléter
4. Remplir le champ **Votre commentaire...**
5. Valider le formulaire en cliquant sur le bouton **Poster**
6. Votre commentaire est publié si tous les champs ont été correctement complétés. Dans le cas contraire, reportez-vous au message d'erreur affiché pour remplir correctement le formulaire

### Modifier un profil utilisateur

Pour modifier votre profil utilisateur, il suffit de :
1. Être connecté sur le site avec votre compte utilisateur. Si vous n'êtes pas connecté, référez-vous aux instructions ci-dessus.
2. Cliquer sur l'onglet **Modifier mon Profil** dans le menu affiché sur la gauche de votre écran
3. Compléter le formulaire en ne remplissant que les champs que vous souhaitez modifier
4. Valider le formulaire en cliquant sur le bouton **Modifier mon Profil**
5. Votre profil est modifié si tous les champs ont été correctement complétés. Dans le cas contraire, reportez-vous au message d'erreur affiché pour remplir correctement le formulaire 


 