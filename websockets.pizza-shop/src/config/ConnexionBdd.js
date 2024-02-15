import knex from "knex";

export const ConnexionBdd = knex({
    client: "mysql",
    connection: {
        host: 'pizza-shop.node.db',
        port: 3306,
        user: 'pizza_shop',
        password: 'pizza_shop',
        database: 'pizza_shop'
    }
}); 