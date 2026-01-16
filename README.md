# Application de Demande de Devis - Coffret de Chantier

Application Laravel pour la gestion des demandes de devis de coffrets de chantier BALS France.

## 📋 Fonctionnalités

- Formulaire interactif de demande de devis
- Prévisualisation en temps réel du contenu de l'email
- Gestion de multiples types de prises (NF, CEI)
- Upload de pièces jointes (PDF, images)
- Génération automatique d'email formaté
- Design responsive compatible mobile

## 🛠️ Technologies utilisées

- Laravel (framework PHP)
- HTML5 / CSS3
- JavaScript (Vanilla)
- Design responsive

## 📦 Structure du projet

```
devis-coffret-app/
├── app/
│   └── Http/
│       └── Controllers/
│           └── DevisController.php    # Contrôleur principal
├── public/
│   ├── css/
│   │   └── style.css                  # Styles de l'application
│   └── js/
│       └── script.js                  # Scripts JavaScript
├── resources/
│   └── views/
│       └── devis-form.blade.php       # Template du formulaire
└── routes/
    └── web.php                        # Routes de l'application
```

## 🚀 Installation

### Prérequis

- PHP >= 8.1
- Composer
- MySQL ou autre base de données compatible
- Node.js et npm (pour compiler les assets si nécessaire)

### Étapes d'installation

1. **Cloner ou télécharger le projet**

2. **Installer Laravel complet** (si pas déjà fait)
   ```bash
   composer create-project laravel/laravel nouveau-projet
   ```

3. **Copier les fichiers de ce projet dans le nouveau projet Laravel**
   ```bash
   # Copier le contrôleur
   cp devis-coffret-app/app/Http/Controllers/DevisController.php nouveau-projet/app/Http/Controllers/
   
   # Copier la vue
   cp devis-coffret-app/resources/views/devis-form.blade.php nouveau-projet/resources/views/
   
   # Copier les assets
   cp devis-coffret-app/public/css/style.css nouveau-projet/public/css/
   cp devis-coffret-app/public/js/script.js nouveau-projet/public/js/
   
   # Copier les routes (ou les intégrer manuellement)
   cat devis-coffret-app/routes/web.php >> nouveau-projet/routes/web.php
   ```

4. **Configuration de l'environnement**
   ```bash
   cd nouveau-projet
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configurer la base de données** dans le fichier `.env`
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=devis_coffret
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Créer le lien symbolique pour le stockage** (pour les pièces jointes)
   ```bash
   php artisan storage:link
   ```

7. **Lancer le serveur de développement**
   ```bash
   php artisan serve
   ```

8. **Accéder à l'application**
   Ouvrez votre navigateur et allez sur : `http://localhost:8000`

## 📧 Configuration de l'email

Pour activer l'envoi automatique d'emails, configurez les paramètres SMTP dans le fichier `.env` :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

Ensuite, décommentez les lignes d'envoi d'email dans `DevisController.php` (méthode `store`).

## 🎨 Personnalisation

### Modifier les couleurs de la charte graphique

Éditez le fichier `public/css/style.css` :

```css
/* Couleurs principales */
#0095DA  /* Bals Blue - couleur principale */
#ED1C24  /* Bals Red - couleur secondaire */
#B3B3B3  /* Light Grey - couleur tertiaire */
```

### Ajouter des types de coffrets

Modifiez le fichier `resources/views/devis-form.blade.php` dans la section "Type de Coffret".

### Personnaliser l'email

Modifiez la méthode `genererContenuEmail()` dans `DevisController.php`.

## 📱 Fonctionnalités du formulaire

1. **Informations de contact** : Distributeur, contact, affaire, téléphone, email
2. **Type de coffret** : Fixe, Mobile, Mobile sur pied
3. **Matériaux** : Caoutchouc, Métallique, Plastique
4. **Indice de protection** : IP44, IP54, IP67
5. **Protection** : 
   - Protection de tête (interrupteur, disjoncteur, etc.)
   - Protection des prises
6. **Prises** : Configuration détaillée des prises (NF, CEI 16A, 32A, 63A, 125A)
7. **Pièces jointes** : Upload de documents PDF et images
8. **Observations** : Zone de texte libre pour informations complémentaires
9. **Prévisualisation** : Affichage en temps réel du contenu de l'email

## 🔒 Sécurité

- Protection CSRF activée sur tous les formulaires
- Validation des données côté serveur
- Limitation de la taille des fichiers uploadés (5 Mo max)
- Types de fichiers autorisés : PDF, JPG, JPEG, PNG, GIF

## 📝 Notes importantes

- Les fichiers uploadés sont stockés dans `storage/app/public/devis-pieces-jointes/`
- Le lien symbolique `public/storage` doit être créé pour accéder aux fichiers
- La fonction d'envoi d'email par défaut ouvre le client email local (mailto)
- Pour un envoi automatique, configurez le SMTP et décommentez le code dans le contrôleur

## 🐛 Résolution de problèmes

### Les styles CSS ne s'affichent pas
```bash
php artisan cache:clear
php artisan view:clear
```

### Erreur 404 sur les routes
```bash
php artisan route:clear
php artisan route:cache
```

### Les fichiers ne s'uploadent pas
```bash
# Vérifier les permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Recréer le lien symbolique
php artisan storage:link
```

## 📞 Support

Pour toute question ou problème, consultez la documentation de Laravel : https://laravel.com/docs

## 📄 Licence

Ce projet est développé pour BALS France.

## 🎯 Roadmap / Améliorations futures

- [ ] Enregistrement des demandes en base de données
- [ ] Interface d'administration pour consulter les demandes
- [ ] Export PDF des demandes
- [ ] Notifications email automatiques
- [ ] Génération de devis au format PDF
- [ ] Historique des demandes par client
- [ ] API REST pour intégration avec d'autres systèmes

---

**Version:** 1.0.0  
**Date:** Janvier 2026  
**Développé pour:** BALS France