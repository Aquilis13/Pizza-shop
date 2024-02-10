<?php

namespace pizzashop\catalog\domain\service;

use pizzashop\catalog\domain\exceptions\CategorieNotFoundException;
use pizzashop\catalog\domain\exceptions\ProductNotFoundException;
use pizzashop\catalog\domain\dto\catalogue\ProduitDTO;
use pizzashop\catalog\domain\entities\catalogue\Produit;
use pizzashop\catalog\domain\entities\catalogue\Categorie;

class ServiceCatalog
{    
    /**
     * accès détaillé à un produit : retourne toutes les
     * informations liées à un produit, y compris sa catégorie et ses tarifs
     * 
     */
    public function accederProduitById(string $id){
        $produit = Produit::find($id);
        $produitDTO = null;

        if (!$produit) {
            throw new ProductNotFoundException("Le produit avec l'id '$id' n'a pas été trouvée.");
        }else{
            $produitDTO =  $this->formateProduitDTO($produit);
        }

        return $produitDTO->toJSON();
    }

    /**
     * Retourne la liste des produits d'une catégorie.
     * 
     */
    public function accederProduitsInCategorie(string $id_categorie){
        $produitDTO = [];

        $categorie = Categorie::find($id_categorie);
        if(!$categorie){
            throw new CategorieNotFoundException("La catégorie d'id ".$id_categorie." n'existe pas.");
        }

        $produits = Produit::where('categorie_id', '=', $id_categorie)->get();
        if ($produits->isEmpty()) {
            throw new ProductNotFoundException("Aucun produits correspondant à la catégorie d'id ".$id_categorie." n'a pas été trouvée.");
        }
        
        foreach($produits as $produit){
            
            array_push($produitDTO, 
                $this->formateProduitDTO($produit)
            );
        } 

        return json_encode($produitDTO, JSON_PRETTY_PRINT);
    }

    /**
     * Retourne la liste des produits proposés ; il s'agit d'une liste complète
     * avec des informations minimales sur chaque produit, étendue avec une référence (URI) vers
     * le produit concerné permettant d'obtenir le détail du produit,
     * 
     */
    public function accederProduitsAll(string $basePath){
        $produitsDTO = [];
        $produits = Produit::all();

        if ($produits->isEmpty()) {
            throw new ProductNotFoundException("Il n'y a aucun produit.");
        }
        
        foreach($produits as $produit){
            $dto = $this->formateProduitDTO($produit);

            array_push($produitsDTO, [
                'libelle' => $dto->libelle_produit,
                'categorie' => $dto->libelle_categorie,
                'detail' => $basePath.'/produits/'.$dto->id
            ]);
        } 

        return json_encode($produitsDTO, JSON_PRETTY_PRINT);
    }

    /**
     * Retourne un produitDTO en fonction d'un produit donner
     */
    private function formateProduitDTO($produit) {
        $tarifs= [];

        foreach($produit->tailles as $taille){
            array_push($tarifs, [
                'taille' => $taille->libelle,
                'tarif' => $taille->pivot->tarif
            ]);
        }

        $produitDTO =  new ProduitDTO(
            $produit->getKey(),
            $produit['numero'],
            $produit['libelle'],
            ($produit->categorie)['libelle'],
            $tarifs
        );

        return $produitDTO;
    }
}
