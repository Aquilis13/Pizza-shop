<?php

namespace pizzashop\catalog\domain\dto\catalogue;

class ProduitDTO extends \pizzashop\catalog\domain\dto\DTO
{
    public ?int $id;
    public ?string $libelle;

    public function __construct(?int $id, ?string $libelle)
    {
        $this->id = $id;
        $this->libelle = $libelle;
    }

}