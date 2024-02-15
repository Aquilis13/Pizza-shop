import { ConnexionBdd } from "../config/ConnexionBdd.js";
import { InvalidCommandStatusException } from "../exceptions/InvalidCommandStatusException.js";
import { CommandeNotFoundException } from "../exceptions/CommandeNotFoundException.js";
import { v4 as uuidv4 } from 'uuid';

export class ServiceCommande {

    constructor() {
        this.connexion = ConnexionBdd;
    }
  
    /**
     * Récupère toutes les commandes de la base et les renvoient
     * 
     * @returns
     */
    async getAllCommandes() {        
        const commandes = await this.connexion
            .select('*')
            .from('commande');
        
        if (commandes.length < 1) {
            throw new CommandeNotFoundException("Aucune données n'est presentes dans la table commande.");
        }

        return commandes;
    }

    /**
     * Réupère une commande on fonction de son id
     * 
     * @param {string} id_commande 
     * @returns 
     */
    async getCommandeById(id_commande) {
        const commande = await this.connexion("commande")
            .where({ id: id_commande })
            .first();

        if (commande.length < 1) {
            throw new CommandeNotFoundException(`Aucune commande ne correspond à l'id ${id_commande}`);
        }

        return commande;
    }

    /**
     * Permet de changer l’état d’une commande. 
     * Elle permet de faire évoluer une commande : REÇUE (1) -> EN PRÉPARATION (2) -> PRÊTE (3) 
     * 
     * @param {string} id_commande 
     * @returns 
     */
    async changerEtatCommande(id_commande) {
        
        // On vérifie si l'état actuel de la commande correspond à REÇUE 
        // ou EN PRÉPARATION avant de le modifier la commande sinon on renvoie une erreur
        const commande = await this.getCommandeById(id_commande);

        if(commande.etape == 3){
            throw new InvalidCommandStatusException("Erreur : Impossible de changer l'état d'une commande PRÊTE.");
        }

        if(commande.etape != 1 && commande.etape != 2){
            throw new InvalidCommandStatusException('Erreur : État commande invalide.');
        }
        
        // Si l'état est actuel est valide on peut le modifier
        const nextEtape = commande.etape + 1;
        await this.connexion("commande")
            .where({ id: id_commande })
            .update({ etape: nextEtape });

        // On récupère ensuite la commande après modification pour la renvoyer
        var commandeUpdate = await this.getCommandeById(id_commande);

        return commandeUpdate;
    }

    /**
     * Crée une commande à partie de l'objet commande fournis en paramètre
     * 
     * @param {json} commande 
     */
    async createCommande(commande) {
        try {
            // Génération d'un identifiant unique s'il n'est pas fourni dans la commande
            const idCommande = commande.id || uuidv4();
    
            // Insertion des informations de commande dans la table "commande"
            await this.connexion("commande").insert({
                date_commande: commande.date_commande || commande.date,
                delai: commande.delai,
                etape: 1,
                id: idCommande,
                mail_client: commande.mail_client,
                montant_total: commande.montant_total,
                type_livraison: commande.type_livraison
            });
    
            // Insertion des items de la commande dans la table "item"
            if (commande.items && commande.items.length > 0) {
                await Promise.all(commande.items.map(async (item) => {
                    await this.connexion("item").insert({
                        id: item.id,
                        numero: item.numero,
                        libelle: item.libelle,
                        taille: item.taille,
                        libelle_taille: item.libelle_taille,
                        tarif: item.tarif,
                        quantite: item.quantite,
                        commande_id: idCommande
                    });
                }));
            }
        } catch (error) {
            // Si on a une erreur on la remonte
            throw error;
        }
    }

}