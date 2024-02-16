import amqp from 'amqplib';

export class ServiceBrokerMessages {

    constructor() {
        this.suivisDesCommandes = new Map(); // Collection qui associe un client socket à un tableau de commande

        // Encode le mot de passe pour ne pas corrompre la chaine de connexion le @
        const rabbitmqPassword = encodeURIComponent('@dm1#!'); 
        this.rabbitmq = `amqp://admin:${rabbitmqPassword}@rabbitmq`;  
    }

    /**
     * Consomme les messages de la queue "suivi_commandes" qui suit les changements d'état des commandes
     * 
     */
    async consommeMessageChangementEtatCommande(client_socket){
        const etapesMapping = {
            1: 'REÇUE',
            2: 'EN PRÉPARATION',
            3: 'PRÊTE'
        };
    
        try {
            const queue = 'suivi_commandes';
    
            const conn = await amqp.connect(this.rabbitmq);
            const channel = await conn.createChannel();
    
            channel.consume(queue, async (msg) => {
                console.log('msg content: ' + msg.content);
                let data = JSON.parse(msg.content);
    
                console.log(data);
                // Vérifie si la propriété "etape" existe dans le message
                if ('etape' in data) {
                    const etatCommande = etapesMapping[data.etape];
    
                    // Vérifie si commandesClient est défini avant d'itérer dessus
                    this.suivisDesCommandes.forEach((commandes, socket) => {
                        if(socket == client_socket){
                            commandes.forEach(function (commande) {
                                if (commande == data.id) {
                                    client_socket.send('Votre commande n°'+data.id+' est passée en état : ' + etatCommande);
                                }
                            });
                        }
                    });
                }
    
                channel.ack(msg);
            });
        } catch (error) {
            throw error;
        }
    }
    
    /**
     * Permet d'ajouter une commande dans la collection suivisDesCommandes
     * 
     */
    addSuivisDesCommandes(client_socket, idCommande) {
        // On vérifie si le client est déjà abonnée à des commandes
        // Si oui on rajoute la commande dans le tableau de commandes qui lui est associer 
        // Sinon on crée le tableau en y ajoutant la commande
        if (this.suivisDesCommandes.has(client_socket)) {
            this.suivisDesCommandes.get(client_socket).push(idCommande);
        } else {
            this.suivisDesCommandes.set(client_socket, [idCommande]);
        }
    }

    /**
     * Getter de pour la collection suivisDesCommandes
     * 
     */
    getSuivisDesCommandes() {
        return this.suivisDesCommandes;
    }
}