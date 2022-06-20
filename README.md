# BileMo

## Contexte

BileMo est une entreprise offrant toute une sélection de téléphones mobiles haut de gamme.
Pas de vente directe de produits sur le site web, elle souhaite fournir à toutes les plateformes qui le souhaitent l’accès au catalogue via une API.
Il s’agit donc de vente exclusivement en B2B (business to business).

## Installation

Commencer par cloner le repository :

```
https://github.com/F-Jean/BileMo.git
cd bilemo
```

Mettre à jour les dépendances :

```
composer update
```

## Configuration

Créer la base de données, créer un fichier `.env.local` :

```
DATABASE_URL=mysql://root:password@127.0.0.1:3306/bilemo
```

Lancer la création :

```
php bin/console doctrine:database:create
```

Installer les fixtures et mettre à jour la base de données

```
composer database
```

## Démarer le serveur et aller sur le site

```
symfony server:start -d
https://127.0.0.1:8000/api/docs
```
