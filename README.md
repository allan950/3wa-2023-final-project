# e-commerce

Requiert:
- PHP >= 8.1.0
- Composer
- Stripe clé API (Un compte est nécessaire pour récupérer une clé API accéssible à partir d'ici: https://dashboard.stripe.com/register). La clé doit être insérer dans un fichier .env.local dans la var PAYMENT_API_KEY
- PHPMyAdmin pour la base de données. Les info de la base de donnée doivent être ajouter dans le fichier .env.local dans la var DATABASE_URL

Commandes à utiliser pour le fonctionnement du projet:
- composer install
- npm install
- symfony composer req encore
