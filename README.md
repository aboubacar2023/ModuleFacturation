# Api Module de Facturation

Mini-module backend de facturation développé en **Laravel 10 / PHP 8.2**, permettant la gestion des clients et des factures avec export JSON.

## Fonctionnalités

- Gestion des **clients**
  - Créer un client (nom, email, siret)
  - Lister tous les clients
  - Voir le détail d’un client
- Gestion des **factures**
  - Créer une facture avec lignes (description, quantité, prix HT, TVA)
  - Calcul automatique du total HT, TVA et TTC
  - Lister toutes les factures
- **Export JSON** d’une facture complète
- Règles métier respectées :
  - Une facture doit avoir au moins une ligne
  - Aucun champ obligatoire ne doit être vide
  - Taux de TVA autorisés : 0%, 5.5%, 10%, 20%

## Authentification avec Laravel Sanctum (l’utilisateur doit être connecté pour accéder aux endpoints)

## Installation rapide
1. git clone https://github.com/aboubacar2023/ModuleFacturation/ && cd ModuleFacturation
2. composer install
3. Copier .env.example en .env
4. Configurer DB: pour SQLite par défaut:
5. php artisan key:generate
6. php artisan migrate
7. php artisan serve
   
## Endpoints principaux
### Clients
- GET /api/clients -> Liste des clients
- POST /api/clients -> Créer un client
- GET /api/clients/{client} -> Détail d’un client

### Factures
- GET /api/factures -> Liste des factures
- POST /api/factures -> Créer une facture
- GET /api/factures/{facture} -> Détail d’une facture
- GET /api/factures/{facture}/export-json -> Export JSON d’une facture

## Tests API
Via Postman ou Insomnia (fichier fourni)

Importer le fichier collection-api.json (dans le dossier CollectionApi) pour les tests Api.
N'oublier les tokens générés car toutes les routes sont protégées. 
