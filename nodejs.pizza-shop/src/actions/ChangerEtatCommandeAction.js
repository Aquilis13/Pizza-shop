import { ServiceCommande } from "../services/ServiceCommande.js";
import { ServiceRabbitMessage } from '../services/ServiceRabbitMessage.js';
  
export const ChangerEtatCommandeAction = async function(req, res, next) {
    try{
        var id_commande = req.params.id;
        var serviceCommande = new ServiceCommande();

        // on récupère la commande avant la modification
        const commande = await serviceCommande.getCommandeById(id_commande);

        const etapesMapping = {
            1: 'REÇUE',
            2: 'EN PRÉPARATION',
            3: 'PRÊTE'
        };
        const etapeCourante = etapesMapping[commande.etape];
        const nextEtape = etapesMapping[commande.etape + 1];
        
        // on récupère la commande après la modification
        const commandeUpdate = await serviceCommande.changerEtatCommande(id_commande);

        // On vérifie si l'état à changer sinon on renvoie un message d'erreur
        if(commande.etape == commandeUpdate.etape){
            res.send({
                status: 'error',
                message: `Une erreur est survenu, l'état de la commande n'a pas pu être modifié`
            });
        }else{
            // Publie la commande modifier
            const serviceRabbitMessage = new ServiceRabbitMessage(serviceCommande);
            serviceRabbitMessage.publishMessage(commandeUpdate);
            
            res.send({
                status: 'success',
                message: `La commande est passée de l'état ${etapeCourante} à l'état ${nextEtape}`,
                datas: commandeUpdate,
                etapes: {
                    1: 'REÇUE',
                    2: 'EN PRÉPARATION',
                    3: 'PRÊTE'
                }
            });
        }
    }catch(error) {
        next(error);
    }
};