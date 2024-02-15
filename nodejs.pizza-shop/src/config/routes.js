import express from "express";
import { AccederCommandesAction } from "../actions/AccederCommandesAction.js";
import { ChangerEtatCommandeAction } from "../actions/ChangerEtatCommandeAction.js";
import { InsererCommandeAction } from "../actions/InsererCommandeAction.js";
import { errorAction } from "../actions/ErrorAction.js";

const router = express.Router();

// Permet d'afficher toutes les commandes 
router.get('/commandes', AccederCommandesAction);

// Permet de valider changer l'état d'une commande
router.patch('/commande/:id', ChangerEtatCommandeAction);

// Permet de tester la création d'une commande avec la commande createCommande
router.post('/commande', InsererCommandeAction);

// middleware d'erreur
router.use(errorAction);

// Changer le middlware d'erreur si elle ne s'affiche pas correctement
// router.use((err, req, res, next) => {
//     res.status(err).json({ error: err });
// });

export default router;
