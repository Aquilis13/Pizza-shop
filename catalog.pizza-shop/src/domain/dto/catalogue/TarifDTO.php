<?php

namespace pizzashop\catalog\domain\dto\catalogue;

use Illuminate\Database\Eloquent\Model;

class TarifDTO extends \pizzashop\catalog\domain\dto\DTO
{
    public string $libelle_taille;
    public float $tarif;

    public function __construct(string $libelle_taille, float $tarif)
    {
        $this->libelle_taille = $libelle_taille;
        $this->tarif = $tarif;
    }
}