# GeoQuizz

## Liste des développeurs :
- Auger Benjamin
- Benchergui Timothée
- Biechy Maxime
- Khenfer Vadim

## Fonctionnalités réalisées :
Toutes les fonctionnalités demandées ont été réalisées. + Fonctionnalités supplémentaires :
- Niveaux de difficulté en choisissant le nombre de photos à trouver et le temps imparti par image
- Le choix de la série de photos à jouer

## Description du projet :
GeoQuizz est un projet de jeu de géolocalisation. Le but du jeu est de trouver la position d'une photo sur une carte. Le joueur doit se déplacer sur la carte pour trouver la position exacte de la photo. Plus le joueur est proche de la position exacte, plus il gagne de points. Le joueur a un temps limité pour trouver la position exacte de la photo.

## Installation du projet :
1. Cloner le projet
   ```bash
    git clone git@github.com:bnj35/Geoquizz.git
   ``` 
2. À la racine du projet, créer deux fichiers "geoquizz.env" et "geoquizzdb.env" et y mettre les variables d'environnement suivantes :
    - geoquizz.env :
      ```env
      JWT_SECRET_KEY='secret'
      ```
      Remplacer "token" par le token d'accès généré sur directus
    - geoquizzdb.env :
      ```env
      POSTGRES_DB=geoquizz
      POSTGRES_USER=root
      POSTGRES_PASSWORD=pass
      ```
   
3. Lancer les conteneurs Docker
   ```bash
    docker-compose up -d
    ```
4. Créer les collections sur directus
   - Se rendre sur http://localhost:8055
   - Se connecter avec les identifiants par défaut (email: admin@exemple.com, mot de passe: d1r3ctu5)
   - Créer une collection "series" avec les champs suivants :
     - id (génération automatique uuid)
     - nom (input de type string)
     - description (input de type string)
   - Créer une collection "images" avec les champs suivants :
     - id (génération automatique uuid)
     - nom (input de type string)
     - latitude (input de type float)
     - longitude (input de type float)
     - mapillary_id (input de type string)
     - serie (relation one to many avec series)
   - Donner toutes les autorisations à l'utilisateur "public" pour les collections "series" et "images" et "directus_files" (pour les images)
   - Générer un token d'accès pour l'utilisateur "admin" et le copier pour le mettre dans un fichier de script php (voir étape suivante). Ne pas oublier de sauvegarder les 3 étapes
   - Aller dans le dossier appGeoquizz puis dans le script php "index.php" et remplacer la valeur de la variable $DIRECTUS_ACCESS_TOKEN (ligne 4) par le token d'accès généré précédemment
   - Lancer le script php avec la commande suivante (dans le dossier appGeoquizz) :
     ```bash
     php index.php
     ```
     Ce script va insérer des données dans les collections "series" et "images" pour tester l'application
4. Aller dans le dossier appGeoquizz/sql et exécuter le script "create_databases.php" et ensuite le script "insert_data.php" pour créer la base de données du jeu avec les commandes suivantes :
     ```bash
     php create_databases.php
     php insert_data.php
     ```
     Ces scripts vont créer la base de données du jeu et insérer des données pour tester l'application


## Front-end :
- Composition API (VueJS 3) 
- Vue Router (SPA)
- Pinia (Store)
- Axios (HTTP)
- TailwindCSS (CSS)
- Leaflet (Map)
- (voir pour autres spécifications

## Back-end :
- Directus (CMS headless)

