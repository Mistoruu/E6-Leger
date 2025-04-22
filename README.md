# E6 - Interface de Catalogue de Formations en Japonais 

Projet réalisé dans le cadre de l’épreuve E6 – BTS SIO SLAM  
Ce site permet aux utilisateurs de consulter un catalogue de formations, créer un compte, ajouter des produits aux favoris, simuler des commandes, et gérer le tout via un espace admin.

## 🛠 Fonctionnalités principales

### Utilisateurs (Frontend)
- Page d’accueil
- Formulaires d’inscription et de connexion
- Catalogue dynamique avec filtres (niveau, prix) et recherche textuelle
- Fiche produit avec image, vidéo, description, prix
- Panier d’achat (ajout/retrait + total dynamique)
- Espace client : historique des commandes + infos personnelles
- Système de favoris
- Simulation d’envoi d’e-mail (réclamation / mot de passe oublié)

### Administrateur (Backend)
- Connexion sécurisée via espace dédié
- Gestion CRUD des produits, utilisateurs, collections, stocks
- Visualisation des commandes client
- Interface développée en PHP et MySQL

##  Stack technique

- **Frontend** : HTML, CSS, JavaScript
- **Backend** : PHP (avec Bcrypt natif pour le hachage des mots de passe)
- **Base de données** : MySQL (modélisé avec mermaid.live)
- **Serveur local** : Apache via XAMPP
- **Outils** : Figma (maquette), GitHub (versioning), MailDev (test e-mails)

##  Sécurité

- Mots de passe utilisateurs hachés avec `password_hash()` (Bcrypt)
- Sessions sécurisées pour l’authentification

##  Installation

### Prérequis :
- XAMPP installé (Apache + MySQL)
- MailDev installé pour tester les emails localement

### Étapes :

1. **Cloner le projet :**
   ```bash
   git clone https://github.com/Mistoruu/E6-Leger.git
   ```
2. **Déplacer le dossier dans le répertoire htdocs de XAMPP :**
3. **Démarrer Apache et MySQL via le panneau de contrôle XAMP**
4. **Importer la base de données :**
- Ouvrir PhpMyAdmin
- Créer une base de données nommée bdd_e5
- Importer le fichier .sql fourni
5. **Lancer MailDev (dans un terminal) :**
  ```bash
  npx maildev
  ```
6. **Accéder au site dans le navigateur :**
- http://localhost/E6-Leger/accueil.php
- Espace admin : http://localhost/E6-Leger/admin.php
