<?php

namespace pizzashop\shop\domain\dto\commande;

class ItemDTO extends \pizzashop\shop\domain\dto\DTO
{
    public int $id;
    public int $numero;
    public string $libelle;
    public string $taille;
    public string $libelle_taille;
    public string $quantite;
    public float $tarif;
    public string $commande_id;
    

    function __construct(int $id, int $numero, string $libelle, string $taille, string $libelle_taille, string $quantite, float $tarif, string $commande_id)
    {
        $this->id = $id;
        $this->numero = $numero;
        $this->libelle = $libelle;
        $this->taille = $taille;
        $this->libelle_taille = $libelle_taille;
        $this->quantite = $quantite;
        $this->tarif = $tarif;
        $this->commande_id = $commande_id;
    }
}