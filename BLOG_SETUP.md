# 📝 Blog Personnel - Laravel 10 + Breeze

Un blog personnel moderne, complètement fonctionnel et prêt pour la production avec Laravel 10, Breeze (authentification), Blade et Tailwind CSS.

## ✨ Fonctionnalités

### 👨‍💼 Admin
- ✅ Créer, modifier, supprimer des articles
- ✅ Gérer les commentaires (répondre, supprimer)
- ✅ Dashboard `/admin/posts`
- ✅ Upload d'images de couverture

### 👤 Utilisateurs Connectés
- ✅ S'inscrire et se connecter
- ✅ Lire les articles publiés
- ✅ Commenter instantanément (sans validation)
- ✅ Répondre à un commentaire
- ✅ Taguer quelqu'un avec `@username`
- ✅ Recevoir une notification lors d'une mention
- ✅ Modifier profil (nom, username, bio)
- ✅ Téléverser un avatar de profil
- ✅ Avatar Gravatar automatique en fallback

### 👻 Visiteurs
- ✅ Lire articles et commentaires
- ✅ Redirigés vers login pour commenter

## 🛠 Stack Technique

- **Backend** : Laravel 10
- **Authentification** : Laravel Breeze (Blade)
- **Frontend** : Blade + Tailwind CSS (CDN)
- **Base de données** : MySQL / MariaDB
- **Avatars** : Gravatar (MD5 email)
- **Fonts** : Google Fonts (Playfair Display + DM Sans)

## 📦 Installation

### 1. Cloner et Installer

```bash
cd blog
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Configurer la Base de Données

Éditer `.env` :
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog
DB_USERNAME=root
DB_PASSWORD=
```

Créer la base de données :
```bash
mysql -u root -p
CREATE DATABASE blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 3. Migrations et Storage

```bash
php artisan migrate
php artisan storage:link
```

### 4. Créer le Compte Admin

```bash
php artisan tinker

# Dans tinker, tapez :
App\Models\User::create([
    'name' => 'BORO NGOBI BILAL',
    'username' => 'admin',
    'email' => 'admin@example.com',
    'bio' => 'Administrateur du blog',
    'role' => 'admin',
    'password' => bcrypt('password'),
]);

# Quitter avec : exit
```

### 5. Démarrer le Serveur

```bash
php artisan serve
```

Accéder à `http://localhost:8000`

## 📁 Structure du Projet

```
app/
├── Models/
│   ├── User.php          (getAvatarUrl(), isAdmin())
│   ├── Post.php          (slug auto-généré, published scope)
│   ├── Comment.php       (replies, mentions)
│   └── Mention.php       (détection @username)
├── Http/
│   ├── Controllers/
│   │   ├── PostController.php       (index, show)
│   │   ├── CommentController.php    (store, destroy + mentions)
│   │   ├── NotificationController.php
│   │   ├── ProfileController.php
│   │   └── Admin/PostController.php (CRUD complet)
│   ├── Middleware/
│   │   └── AdminMiddleware.php      (vérifie isAdmin())
├── Notifications/
│   └── MentionedInComment.php       (canal: database)
└── ...

database/
├── migrations/
│   ├── create_users_table.php       (username, bio, role)
│   ├── create_posts_table.php
│   ├── create_comments_table.php    (parent_id pour réponses)
│   ├── create_mentions_table.php
│   └── create_notifications_table.php

resources/views/
├── layouts/
│   └── app.blade.php                (navbar, notifications, footer)
├── posts/
│   ├── index.blade.php              (grille responsive)
│   └── show.blade.php               (commentaires)
├── comments/
│   └── _comment.blade.php           (récursif + @mentions colorés)
├── admin/posts/
│   ├── index.blade.php              (tableau CRUD)
│   ├── create.blade.php
│   └── edit.blade.php
├── notifications/
│   └── index.blade.php
└── profile/
    └── edit.blade.php               (Gravatar + username)

routes/
└── web.php                          (routes publiques, auth, admin)
```

## 🔗 Routes Principales

### Publiques
- `GET /` → Articles publiés
- `GET /posts/{post:slug}` → Détail article

### Authentifiées
- `POST /posts/{post}/comments` → Créer commentaire
- `DELETE /comments/{comment}` → Supprimer commentaire
- `GET /notifications` → Liste notifications
- `GET /profile` → Éditer profil
- `PATCH /profile` → Mettre à jour profil

### Admin (`/admin`)
- `GET /admin/posts` → Dashboard articles
- `GET /admin/posts/create` → Créer article
- `POST /admin/posts` → Stocker article
- `GET /admin/posts/{post}/edit` → Éditer article
- `PUT /admin/posts/{post}` → Mettre à jour
- `DELETE /admin/posts/{post}` → Supprimer

## 🎨 Design & Couleurs

Variables CSS personnalisées :
```css
--ink: #1a1a2e         (texte principal)
--cream: #f8f6f0       (arrière-plan)
--accent: #c9440e      (orange actions)
--muted: #6b6b7b       (texte secondaire)
```

Fonts :
- **Playfair Display** → Titres (font-playfair)
- **DM Sans** → Corps (font-sans)

## 🚀 Déploiement Production

### Environment `.env`
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com
LOG_CHANNEL=stack
SESSION_DRIVER=database
CACHE_DRIVER=file
QUEUE_CONNECTION=database
FILESYSTEM_DRIVER=public
```

### Commands
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan storage:link
php artisan queue:work --daemon
```

### Conseils
- Utiliser `APP_DEBUG=false` en production.
- Régler `APP_URL` sur votre URL publique.
- Ne pas exposer `.env`.
- Donner au dossier `storage` et `bootstrap/cache` les droits nécessaires.
- Configurer Nginx/Apache pour pointer vers `public/`.

### Web Server (Nginx exemple)
```nginx
server {
    listen 80;
    server_name blog.example.com;
    root /var/www/blog/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.html index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 📝 Points Importants

✅ **Pas d'approbation des commentaires** - Publiés instantanément  
✅ **Pas d'upload d'avatar** - Gravatar uniquement  
✅ **Slug auto-généré** - À partir du titre  
✅ **Admin manuel** - Créé via `php artisan tinker`  
✅ **Storage link obligatoire** - Pour les images de couverture  
✅ **Middleware admin enregistré** - Dans `app/Http/Kernel.php`

## 🔐 Sécurité

- ✅ CSRF protection (tous les formulaires)
- ✅ Delete methods avec `@method('DELETE')`
- ✅ Validation stricte (server-side)
- ✅ Auteurs/admins seulement pour supprimer
- ✅ MD5 Gravatar avec fallback identicon

## 🐛 Troubleshooting

### "SQLSTATE Access denied"
Vérifier credentials `.env` et créer base de données

### "File not found" (images)
Exécuter : `php artisan storage:link`

### Migrations échouées
```bash
php artisan migrate:refresh
php artisan migrate
```

### Cache issues
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 📄 Licences

- Laravel : MIT
- Breeze : MIT
- Tailwind CSS : MIT

## 👨‍💻 Développement

Créé avec ❤️ pour un blog personnel production-ready.
