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