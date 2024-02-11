<?php

namespace pizzashop\catalog\domain\dto\catalogue;

class ProduitDTO extends \pizzashop\catalog\domain\dto\DTO
{
    public int $id;
    public int $numero_produit;
    public string $libelle_produit;
    public string $description;
    public string $libelle_categorie;
    public $tarifs;

    public function __construct(int $id, int $numero_produit, string $libelle_produit, string $description, string $libelle_categorie, $tarifs)
    {
        $this->id = $id;
        $this->numero_produit = $numero_produit;
        $this->libelle_produit = $libelle_produit;
        $this->description = $description;
        $this->libelle_categorie = $libelle_categorie;
        $this->tarifs = $tarifs;
    }

}