# FlashProject - The Power Of Memory

## Introduction
Bienvenue sur **The Power Of Memory** ! Ce projet a été réalisé dans le cadre d'un sprint de 4 semaines, couvrant les bases de 5 langages web : **HTML, CSS, JavaScript, SQL et PHP**.

L'objectif était de concevoir un site web interactif proposant un jeu de mémoire tout en intégrant des fonctionnalités bonus. Le développement a été organisé en 4 étapes :
- Implémentation de l'interface en **HTML / CSS**
- Dynamisation avec **JavaScript**
- Gestion des utilisateurs et des scores avec **PHP**
- Stockage des données avec **SQL**

---

## Présentation du projet
**The Power Of Memory** est un site permettant aux utilisateurs de tester leur mémoire à travers un jeu de cartes interactif. Le principe est simple : une grille aléatoire de cartes est générée en fonction de la difficulté choisie. Chaque carte est associée à une image cachée, et l'utilisateur doit retrouver les paires le plus rapidement possible.

À la fin de la partie, le score du joueur est enregistré et comparé aux autres scores dans un tableau de classement. Seuls les 10 meilleurs scores par niveau de difficulté sont affichés pour permettre aux utilisateurs de repousser leurs limites !

---

## Structure du site
Le projet comprend plusieurs pages essentielles :

- **Page d'accueil** : Présentation du site et de ses fonctionnalités.
- **Page de login** : Identification des utilisateurs pour accéder au jeu et aux fonctionnalités personnalisées.
- **Page d'inscription** : Permet aux nouveaux utilisateurs de créer un compte.
- **Page du jeu** : L'interface principale où se déroule le jeu de mémoire.
- **Page de contact** : Formulaire permettant aux utilisateurs de poser des questions ou soumettre des suggestions.
- **Page des scores** : Tableau affichant les meilleurs scores triés par niveau de difficulté.
- **Page mon compte** : Permet aux utilisateurs de modifier leurs informations personnelles (email et mot de passe).

Des maquettes des pages nous ont été fournies afin de guider la conception graphique du site.

---

## Fonctionnalités principales
Le site intègre plusieurs fonctionnalités interactives :

- **Connexion / Inscription** : Validation des formulaires et gestion sécurisée des utilisateurs.
- **Le jeu** :
  - Choix parmi 4 niveaux de difficulté :
    - **Facile** (4x4)
    - **Intermédiaire** (6x6)
    - **Expert** (8x8)
    - **Impossible** (10x10)
  - Génération dynamique de la grille avec des images aléatoires.
  - Calcul du temps de jeu pour établir un score.
- **Tableau des scores** :
  - Tri des scores selon les performances.
  - Recherche par pseudo pour retrouver un joueur spécifique.
- **Chat en direct** : Présent sur la page du jeu pour permettre aux joueurs d'échanger en temps réel.
- **Formulaire de contact** : Envoi d'un email à l'administrateur du site.
- **Choix du thème du jeu** : 3 thèmes disponibles (Voitures, Logo de marques, Animaux) modifiant les images utilisées dans le jeu.

---

## Technologies utilisées
- **Frontend** : HTML, CSS, JavaScript
- **Backend** : PHP
- **Base de données** : MySQL

---

## Installation et exécution
1. **Clonez ce dépôt**
2. **Configuration de la base de données** :
   - Importez le fichier SQL fourni dans votre serveur MySQL.
3. **Lancement du serveur local** :
   - Avec MAMP, placez les fichiers dans htdocs et démarrez Apache et MySQL.
   - Accédez au site via `http://localhost:8888/index.php`.

---

## Auteurs
Projet réalisé en équipe avec la participation de :  
- **Sellia NADE** : https://github.com/JinSeeng
- **Léo GASCUENA** : https://github.com/leo-gsc
- **Heidi IROUDAYARADJOU** : https://github.com/Heidi15
- **Damien RENARD** : https://github.com/MonsoonD
