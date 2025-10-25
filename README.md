<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Service d'Organization - API Backend EasySMI

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat&logo=php)](https://php.net)

## Description

Le service d'organisation fait partie de la plateforme EasySMI et gère toutes les fonctionnalités liées à la gestion des organisations, des utilisateurs et des structures hiérarchiques.

## Architecture Modulaire

Ce projet utilise une architecture modulaire avec les modules suivants :

### Modules Disponibles

- **Organization** : Gestion des organisations et structures hiérarchiques
- **Auth** : Système d'authentification et gestion des utilisateurs

## Démarrage Rapide

### Prérequis

- PHP 8.1 ou supérieur
- Composer
- Node.js et npm
- Git
- MySQL

### Installation

1. **Cloner le projet**
```bash
git clone https://repo.hesystems-group.com/easysmi/easy-smi-pro.git
cd easy-smi-pro
```

2. **Installer les dépendances PHP**
```bash
composer install
```

3. **Installer les dépendances JavaScript**
```bash
npm install
```

4. **Configurer l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configurer la base de données**
```bash
# Modifier le fichier .env avec vos paramètres de base de données
# Puis exécuter les migrations avec seeders
php artisan migrate
php artisan db:seed
```

6. **Démarrer le serveur**
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

L'API sera disponible sur `http://localhost:8000/api`

## Configuration

### Variables d'environnement importantes

| Variable | Description | Défaut |
|----------|-------------|---------|
| `APP_DEBUG` | Mode debug | `true` |
| `DB_HOST` | Hôte base de données | `127.0.0.1` |
| `DB_DATABASE` | Nom de la base | `easysmi_pro` |
| `JWT_SECRET` | Clé secrète JWT | Générée automatiquement |

### Services externes configurés

- **Authentification** : JWT, Laravel Sanctum
- **Stockage** : Local (extensible vers AWS S3)
- **Base de données** : MySQL

## Tests

```bash
# Lancer tous les tests
php artisan test

# Tests avec couverture
php artisan test --coverage
```

## Déploiement

### Avec Docker (Recommandé)

1. **Démarrer avec Docker**
```bash
docker-compose up -d
```

2. **Vérifier les logs**
```bash
docker-compose logs -f app
```

### Déploiement traditionnel

```bash
# Construire les assets
npm run build

# Optimisation pour la production
php artisan optimize
php artisan view:cache
php artisan config:cache
php artisan route:cache
```

## Structure du Projet

```
app/
├── Modules/
│   ├── Auth/
│   │   ├── Controllers/
│   │   ├── Models/
│   │   ├── Requests/
│   │   ├── routes/
│   │   └── AuthServiceProvider.php
│   └── Organization/
│       ├── Controllers/
│       ├── Models/
│       ├── Requests/
│       ├── routes/
│       └── OrganisationServiceProvider.php
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Models/
└── Providers/
```

## Sécurité

- ✅ Fichiers sensibles ignorés (.env, clés privées)
- ✅ Validation des requêtes avec Laravel Requests
- ✅ Middleware d'authentification configuré
- ✅ Protection CSRF activée

## Contribution

1. Fork le projet
2. Créer une branche (`git checkout -b fonctionnalite/FeatureIncredible`)
3. Commit les changements (`git commit -m 'Ajouter une fonctionnalité incroyable'`)
4. Push la branche (`git push origin fonctionnalite/FeatureIncredible`)
5. Ouvrir une Pull Request

### Standards de code

- PSR-12 pour le style PHP
- Tests unitaires pour toute nouvelle fonctionnalité
- Documentation PHPDoc obligatoire

## License

Ce projet fait partie de la plateforme EasySMI et est sous licence propriétaire.

## Support

Pour toute question ou problème :
- Ouvrir une issue sur votre plateforme de gestion de projet
- Contacter l'équipe de développement

---

**Développé  par l'équipe EasySMI**
