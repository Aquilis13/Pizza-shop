<?php

namespace pizzashop\shop\domain\entities\commande;

use pizzashop\shop\domain\dto\catalogue\ItemDTO;

class Item extends \Illuminate\database\eloquent\Model
{
    protected $connection = 'commande';
    protected $table = 'item';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['id','numero', 'libelle','taille', 'libelle_taille', 'tarif', 'quantite', 'commande_id'];

    public function commande(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }

}