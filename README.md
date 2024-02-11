# Nom 
**CHANOT Flora**

# Routes
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

## Prerequis
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