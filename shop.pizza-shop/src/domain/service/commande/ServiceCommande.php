<?php

namespace pizzashop\shop\domain\service\commande;

use Ramsey\Uuid\Uuid;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

use pizzashop\shop\domain\dto\commande\CommandeDTO;
use pizzashop\shop\domain\entities\commande\Commande;
use pizzashop\shop\domain\entities\commande\Item;
use pizzashop\shop\domain\entities\catalogue\Categorie;
use pizzashop\shop\domain\dto\catalogue\CategorieDTO;
use pizzashop\logs\CommandeLogger;
use pizzashop\shop\domain\exceptions\ServiceCommandeNotFoundException;

class ServiceCommande
{    
    public function __construct(){
        $logger = new CommandeLogger('test', 'info');
    }

    /**
     * Permet d'obtenir une description complète de la commande,
     * incluant la liste d'items commandés.
     * 
     */
    public function accederCommande(string $UUID){
        $commandeDTO = null;
        $commande = Commande::find($UUID);

        if (!$commande) {
            throw new ServiceCommandeNotFoundException("La commande avec l'UUID '$UUID' n'a pas été trouvée.");
        }else{
            $items = Item::where('commande_id', '=', $commande['id'])->get();
            $commandeDTO = new CommandeDTO(
                $commande['id'], 
                $commande['date_commande'], 
                $commande['type_livraison'],
                $commande['mail_client'],
                $commande['montant_total'], 
                $commande['etat'],
                $commande['delai'],
                $items
            );
        }
        return $commandeDTO->toJSON();
    }

    /**
     * Permet de faire passer une commande de l'état CREE à l'état VALIDE.
     * 
     */
    public function validerCommande(string $UUID){
        $commandeDTO = null;
        $commande = Commande::find($UUID);

        if (!$commande) {
            throw new ServiceCommandeNotFoundException("La commande avec l'UUID $uuid n'a pas été trouvée.");
        }else{      
            if($commande->etat == Commande::CREE){
                $commande->etat = Commande::VALIDE;
                $commande->save();
            }

            $items = Item::where('commande_id', '=', $commande['id'])->get();
            $commandeDTO = new CommandeDTO(
                $commande['id'], 
                $commande['date_commande'], 
                $commande['type_livraison'],
                $commande['mail_client'],
                $commande['montant_total'], 
                $commande['etat'],
                $commande['delai'],
                $items
            );   
        }        
        return $commandeDTO->toJSON();
    }

    /**
     * Permet de créer une nouvelle commande
     * 
     */
    public function creerCommande(CommandeDTO $commandeDTO){
        $uuid = Uuid::uuid4();
        $commandeDTO->id = $uuid->toString();
        
        $newCommande = new Commande([
            'id' => $commandeDTO->id,
            'date_commande' => $commandeDTO->date,
            'type_livraison' => $commandeDTO->type_livraison,
            'montant_total' => $commandeDTO->montant,
            'etat' => Commande::CREE,
            'mail_client' => $commandeDTO->mail_client,
        ]);
        $newCommande->save();
        $logger = new CommandeLogger("Nouvelle commande ajouter avec l'id $commandeDTO->id", "info");

        foreach($commandeDTO->items as $item){
            $assosItem = new Item([
                'numero' => isset($item['numero']) ? $item['numero'] : null,
                'libelle' => isset($item['libelle']) ? $item['libelle'] : null,
                'taille' => isset($item['taille']) ? $item['taille'] : null,
                'libelle_taille' => isset($item['libelle_taille']) ? $item['libelle_taille'] : null,
                'tarif' => isset($item['tarif']) ? $item['tarif'] : null,
                'quantite' => isset($item['quantite']) ? $item['quantite'] : null,
                'commande_id' => $commandeDTO->id,
            ]);          
            $assosItem->save();
            $numero = $item['numero'];
            $logger = new CommandeLogger("L'item n° $numero a était associer à la commande avec l'id $commandeDTO->id", "info");
        }

        return $commandeDTO->toJSON();
    }
}