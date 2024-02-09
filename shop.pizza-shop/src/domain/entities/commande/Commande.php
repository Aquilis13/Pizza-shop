<?php

namespace pizzashop\shop\domain\entities\commande;

use Illuminate\Database\Eloquent\Model;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;
use pizzashop\shop\domain\exceptions\ServiceException;
use pizzashop\shop\domain\dto\commande\CommandeDTO;


class Commande extends Model
{
    // Constantes d'état
    const CREE = 1;
    const VALIDE = 2;

    // Constantes de la livraison
    const LIVRAISON_A_DOMICILE = 1;
    const LIVRAISON_A_EMPORTER = 2;
    const CONSOMMATION_SUR_PLACE = 3;

    protected $connection = 'commande';
    protected $table = 'commande';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['id', 'date_commande', 'type_livraison','montant_total', 'etat', 'mail_client', 'delai'];


    public function items()
    {
        return $this->hasMany(Item::class, 'id_item');
    }

    public static function valideDTO(CommandeDTO $DTO)
    {
        try {
            v::key('mail_client', v::email())
                ->key('type_livraison', v::in([1, 2, 3]))
                ->key('items', v::arrayVal()->notEmpty()
                    ->each(v::arrayVal()
                        ->key('numero', v::intVal()->positive())
                        ->key('quantite', v::intVal()->positive())
                        ->key('taille', v::in([1, 2]))
                    )
                )
                ->key('id', v::optional(v::stringType()))
                ->key('date', v::optional(v::stringType()))
                ->key('montant', v::optional(v::numericVal()))
                ->key('etat', v::optional(v::intVal()))
                ->key('delai', v::optional(v::intVal()))
                ->assert($DTO);
        } catch(NestedValidationException $e) {
            throw new ServiceException('erreur '.$e->getFullMessage());
        }
    }
}