<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Demande de Devis - Coffret de Chantier | BALS France</title>
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">
    
    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body>
    <!-- En-tête -->
    <div style="background-color: #0095DA; padding: 20px 40px; color: white;">
        <h1 style="margin: 0; font-size: 24px;">🔌 Demande de Devis - Coffret de Chantier</h1>
        <p style="margin: 5px 0 0 0; font-size: 14px;">📧 À envoyer à : <strong>info@bals-france.fr</strong></p>
    </div>

    <!-- Composant Livewire -->
    <div style="background-color: #f5f5f5;">
        @livewire('devis-form-wizard')
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>
```

## 📝 Étapes complètes pour corriger l'erreur

### 1. Vérifier la structure de votre projet

Votre projet doit avoir cette structure :
```
votre-projet-laravel/
├── app/
│   └── Http/
│       └── Livewire/
│           └── DevisFormWizard.php        ← À créer
├── resources/
│   └── views/
│       ├── devis-wizard.blade.php         ← À créer (fichier principal)
│       └── livewire/
│           └── devis-form-wizard.blade.php ← À créer (template)
└── public/
    └── css/
        └── wizard.css                      ← À créer