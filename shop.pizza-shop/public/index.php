<?php
declare(strict_types=1);

use pizzashop\shop\domain\service\commande\ServiceCommande;


require_once __DIR__ . '/../vendor/autoload.php';

/* application boostrap */
$appli = require_once __DIR__ . '/../config/bootstrap.php';

(require_once __DIR__ . '/../config/routes.php')($appli);

$appli->run();