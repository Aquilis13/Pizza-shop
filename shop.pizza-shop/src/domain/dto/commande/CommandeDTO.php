<?php

namespace pizzashop\shop\domain\dto\commande;

class CommandeDTO extends \pizzashop\shop\domain\dto\DTO
{
    public ?string $id;
    public ?string $date;
    public ?int $type_livraison;
    public ?string $mail_client;
    public ?float $montant;
    public ?int $etat;
    public ?int $delai;
    public $items;

    function __construct(?string $id, ?string $date, ?int $type_livraison, ?string $mail_client, ?float $montant, ?int $etat, ?int $delai, $items)
    {
        $this->id = $id;
        $this->date = $date;
        $this->type_livraison = $type_livraison;
        $this->mail_client = $mail_client;
        $this->montant = $montant;
        $this->etat = $etat;
        $this->delai = $delai;
        $this->items = $items;
    }
}