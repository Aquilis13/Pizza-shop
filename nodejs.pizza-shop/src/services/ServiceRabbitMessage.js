import amqp from 'amqplib';

export class ServiceRabbitMessage {

    constructor(serviceCommande) {
        this.serviceCommande = serviceCommande;
        
        // Encode le mot de passe pour ne pas corrompre la chaine de connexion le @
        const rabbitmqPassword = encodeURIComponent('@dm1#!'); 
        this.rabbitmq = `amqp://admin:${rabbitmqPassword}@rabbitmq`;  
    }

    /**
     * Consomme les messages de la queue "nouvelle_commande" et les ajoutes à la base de données
     * 
     */
    async consommeMessage(){
        try {
            const queue = 'nouvelles_commandes';
            
            const conn = await amqp.connect(this.rabbitmq);
            const channel = await conn.createChannel();
            
            channel.consume(queue, async (msg) => {
                console.log('msg content: ' + msg.content);

                let data = JSON.parse(msg.content);

                await this.serviceCommande.createCommande(data);
                console.log('Commande ajoutée à la base de données.');

                channel.ack(msg);
            });
        } catch (error) {
            throw error;
        }
    } 

    /**
     * Publie un message dans la route "suivi_commandes" avec l’exchange "pizzashop" et la routing key "suivi"
     * 
     * @param {string} commandeData 
     */
    async publishMessage(commandeData){
        try {
            const exchange = 'pizzashop';
            const routingKey = 'suivi';

            const conn = await amqp.connect(this.rabbitmq);
            const channel = await conn.createChannel(); 

            const msg = JSON.stringify(commandeData);
            channel.publish(exchange, routingKey, Buffer.from(msg));
        } catch (error) {
            throw error;
        }
    } 
}