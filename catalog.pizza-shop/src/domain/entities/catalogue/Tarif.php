<?php

namespace pizzashop\catalog\domain\entities\catalogue;

use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    protected $connection = 'catalog';
    protected $table = 'tarif';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['tarif'];

    public function produit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }

    public function taille(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Taille::class, 'taille_id');
    }
}
