import express from "express";
import helmet from "helmet";
import router from './config/routes.js';
import { ServiceCommande } from './services/ServiceCommande.js';
import { ServiceRabbitMessage } from './services/ServiceRabbitMessage.js';

const serviceCommande = new ServiceCommande();
const serviceRabbitMessage = new ServiceRabbitMessage(serviceCommande);

const port = 3000;
const app = express();

app.use(express.json());
app.use(express.urlencoded({ extended: false }));
app.use(helmet());
app.use('/', router);

serviceRabbitMessage.consommeMessage();

app.listen(port, () => { 
    console.log(`listening on ${port}!`);
});
