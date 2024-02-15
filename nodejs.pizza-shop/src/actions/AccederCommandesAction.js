import { ServiceCommande } from "../services/ServiceCommande.js";

export const AccederCommandesAction = async function(req, res, next) {
    var serviceCommande = new ServiceCommande();
    
    serviceCommande.getAllCommandes()
        .then(commandes => {
            res.send({
                status: 'success',
                datas: commandes
            });
        })
        .catch(error => {
            next(error);
        });
};
