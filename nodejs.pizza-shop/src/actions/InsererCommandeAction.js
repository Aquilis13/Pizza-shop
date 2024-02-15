import { ServiceCommande } from "../services/ServiceCommande.js";
  
export const InsererCommandeAction = async function(req, res, next) {
    try {
        const serviceCommande = new ServiceCommande();
        
        const commandeData = req.body; 
        await serviceCommande.createCommande(commandeData);
    
        res.send({
            status: 'success',
            message: 'La commande a été insérée avec succès'
        });
    } catch(error) {
        next(error);
    }
};