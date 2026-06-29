# E-Com ERP - Système de Gestion Intégré

Application web développée en Laravel permettant la gestion centralisée des commandes, du catalogue de produits, de la logistique et de la relation client (CRM) pour une activité e-commerce.

## Fonctionnalités Clés

- Tableau de bord directionnel : Suivi financier (chiffre d'affaires, bénéfice net) et graphique d'évolution des commandes (Chart.js).
- Gestion de catalogue : CRUD complet avec système d'alerte pour les stocks faibles.
- Logistique automatisée : Utilisation des Observers Laravel pour décrémenter les stocks lors de l'expédition des commandes.
- CRM et Analyse de risque : Calcul automatique du taux de livraison par client pour identifier les profils problématiques.

## Architecture Technique

- Framework : Laravel 11
- Authentification : Laravel Breeze
- Front-end : Blade, Tailwind CSS, Alpine.js
- Bonnes pratiques : Skinny Controllers, Form Requests pour la validation, Observers pour la logique métier.

## Guide d'Installation

```bash
# Cloner le dépôt
git clone <repository-url>
cd ecom-erp

# Installer les dépendances PHP
composer install

# Configurer l'environnement
cp .env.example .env
php artisan key:generate

# Exécuter les migrations avec les données de test (sur 3 mois)
php artisan migrate:fresh --seed

# Installer et compiler les assets front-end
npm install && npm run build

# Démarrer le serveur de développement
php artisan serve
```

L'application sera accessible à l'adresse http://localhost:8000

