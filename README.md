# 🚀 Projet PHP & MySQL avec Docker (Sans XAMPP)

Ce projet est un environnement de développement local conteneurisé avec **Docker** et **Docker Compose**, associant un serveur **PHP 8.2 / Apache** et une base de données **MySQL 8.0**. Il permet de s'affranchir complètement d'installations lourdes et fixes comme XAMPP.

---

## 🛠️ Prérequis

Assurez-vous d'avoir installé sur votre machine :
- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- Git

---

## 📂 Structure du Projet

```text
.
├── Dockerfile          # Configuration personnalisée de l'image PHP/Apache (avec PDO MySQL)
├── docker-compose.yml  # Orchestration des services Web et Base de données
└── html                # Dossier racine de votre site web (contient index.php)
