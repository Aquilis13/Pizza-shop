import WebSocket, { WebSocketServer } from 'ws';
import { ServiceBrokerMessages } from './services/ServiceBrokerMessages.js';

const server= new WebSocketServer({ port: 3000 , clientTracking: true});

const serviceBrokerMessages = new ServiceBrokerMessages();

server.on('connection', (client_socket) => {
    serviceBrokerMessages.consommeMessageChangementEtatCommande(client_socket);

    client_socket.addEventListener('error', console.error);

    client_socket.addEventListener('message', (event) => {
        var idCommande = event.data;

        // On ajoute la commande aux abonements du client
        serviceBrokerMessages.addSuivisDesCommandes(client_socket, idCommande);    

        // Après avoir enregistre le suivi de commande on envoie un message au client
        client_socket.send('Vous vous êtes abonné au suivi de cette commande : ' + idCommande);
    });

    client_socket.addEventListener('close', (event) => {
        console.log('client disconnected ');
    });
})
