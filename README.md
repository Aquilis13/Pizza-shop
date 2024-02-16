# Nom 
**CHANOT Flora**

# Informations générales
[Lien du dépôt GitHub](https://github.com/Aquilis13/Pizza-shop)  
  
On peut lancer les container avec ``docker compose up`` ou ``docker compose up -d``  
  
Pour la création des base les script se trouvent dans le dossier ``sql`` des APIs :
- [Script SQL API auth](https://github.com/Aquilis13/Pizza-shop/tree/main/auth.pizza-shop/sql)
- [Script SQL API commande](https://github.com/Aquilis13/Pizza-shop/tree/main/shop.pizza-shop/sql)
- [Script SQL API catalogue](https://github.com/Aquilis13/Pizza-shop/tree/main/catalog.pizza-shop/sql)
- [Script SQL API NodeJS](https://github.com/Aquilis13/Pizza-shop/tree/main/nodejs.pizza-shop/sql)

# Routes APIs PHP via API Gateway
### API Commande

- **Créer une commande :**   
    Endpoint: http://0.0.0.0:8000/commandes  
    Method: POST  

- **Accéder à une commande :**   
    Endpoint: http://0.0.0.0:8000/commandes/{id_commande}  
    Method: GET  

- **Valider une commande :**  
    Endpoint: http://0.0.0.0:8000/commandes/{id_commande}  
    Method: PATCH   

### API Catalogue

- **Accéder aux produits :**  
    Endpoint: http://0.0.0.0:8000/produits  
    Method: GET  

- **Accéder au détail d'un produit :**  
    Endpoint: http://0.0.0.0:8000/produits/{id_produit}  
    Method: GET  

- **Produits par Catégorie :**  
    Endpoint: http://0.0.0.0:8000/categories/{id_categorie}/produits  
    Method: GET  

### API Authentification

- **Inscription :**  
    Endpoint: http://0.0.0.0:8000/api/users/signup  
    Method: POST  

- **Connexion :**  
    Endpoint: http://0.0.0.0:8000/api/users/signin  
    Method: POST  

- **Validation d'un jeton :**  
    Endpoint: http://0.0.0.0:8000/api/users/validate  
    Method: GET  

- **Actualisation d'un Token :**  
    Endpoint: http://0.0.0.0:8000/api/users/refresh  
    Method: POST  

# TD8
## Localisation
- [Exercice 1](https://github.com/Aquilis13/Pizza-shop/blob/main/shop.pizza-shop/script_rabbitmq/exercice1.php)
- [Exercice 2](https://github.com/Aquilis13/Pizza-shop/blob/main/shop.pizza-shop/script_rabbitmq/exercice2.php)
- [Exercice 3](https://github.com/Aquilis13/Pizza-shop/blob/main/shop.pizza-shop/script_rabbitmq/exercice3.php)

## Prérequis
Pour pouvoir executer les scripts :  
```
docker compose up -d
docker compose exec api.pizza-shop bash
```

## Script php
Dans le container à partir de ``var/www/`` :  
```
cd script_rabbitmq
```

**Executer le script de l'exercice 1 :**
```
php exercice1.php
```

**Executer le script de l'exercice 2 :**
```
php exercice2.php
```

**Executer le script de l'exercice 3 :**
```
php exercice3.php
```

# Conf rabbitMQ pour les TD 9 et 10
## Connexion au serveur
Nom d'utilisateur : ``admin``  
Mot de passe : ``@dm1#!``  

## Config :
**Exchange :** ``pizzashop`` de type "DIRECT"

**Queue en type classic avec la propriété Durable :**  
- ``nouvelles_commandes``
- ``suivi_commandes``

**Binding dans l'exchange "pizzashop" :**   
- Routing Key : ``nouvelle`` associer à la queue ``nouvelles_commandes``
- Routing Key : ``suivi`` associer à la queue ``nouvelles_commandes``

# TD9 - API nodeJS
## Infos
S'il y a une erreur au lancement des container qui stop l'appli il faut modifier un fichier en rajoutant un espace et enregistrer pour que l'appli redémarre.  

Pour installer les dépendances dans le container : 
```
npm install
```
## Routes

- **Afficher la liste des commandes :**   
    Endpoint: http://0.0.0.0:3333/commandes  
    Method: GET  

- **Passer une commande à l'étape suivante :**   
    Endpoint: http://0.0.0.0:3333/commande/:id   
    Method: PATCH  

- **Crée une nouvelle commande (Route de test):**  
    Endpoint: http://0.0.0.0:3333/commande  
    Method: POST   

# TD10 - Websockets :
  
Dans postman ou autre : ``ws://0.0.0.0:7777``  
Pour installer les dépendances dans le container : 
```
npm install
```

## Fonctionnement 
- Passer les id des commandes à suivre dans la zone de message
- On peut changer l'état des commandes à partir de l'API NodeJS avec la route suivante : http://0.0.0.0:3333/commande/:id   

Les changements d'état apparaîtront dans les messages du websocket.