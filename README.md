# 🚀 Projet PHP & MySQL avec Docker (Gestion de Profils & Images)

Ce projet est un environnement de développement local conteneurisé avec **Docker** et **Docker Compose**, associant un serveur **PHP 8.2 / Apache** et une base de données **MySQL 8.0**. Il permet de s'affranchir complètement de XAMPP.

Cette nouvelle version inclut :
- Un formulaire d'enregistrement dynamique (Nom, Email, Photo de profil).
- Un système d'upload d'images sécurisé géré par PHP.
- Une base de données MySQL persistante via les volumes Docker.

---

## 🛠️ Prérequis

Assurez-vous d'avoir installé sur votre machine :
- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- Git

---

## 📂 Structure du Projet

```text
mon-projet-docker/
├── Dockerfile          # Configuration personnalisée de l'image PHP/Apache (avec PDO MySQL)
├── docker-compose.yml  # Orchestration des services Web (PHP) et Base de données (MySQL)
└── html/               # Dossier racine du site web
    ├── index.php       # Logique de connexion, formulaire et affichage
    └── uploads/        # Dossier de stockage des images téléversées