<?php

namespace pizzashop\tests\commande;

use Faker\Factory;
use PHPUnit\Framework\Attributes\DataProvider;
use pizzashop\shop\domain\entities\commande\Commande;
use pizzashop\shop\domain\entities\commande\Item;
use Illuminate\Database\Capsule\Manager as DB;

class ServiceCommandeTest extends \PHPUnit\Framework\TestCase {

    private static $commandeIds = [];
    private static $itemIds = [];
    private static $serviceProduits;
    private static $serviceCommande;
    private static $faker;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        $dbcom = __DIR__ . '/../../config/commande.db.test.ini';
        $dbcat = __DIR__ . '/../../config/catalog.db.ini';
        $db = new DB();
        $db->addConnection(parse_ini_file($dbcom), 'commande');
        $db->addConnection(parse_ini_file($dbcat), 'catalog');
        $db->setAsGlobal();
        $db->bootEloquent();

        self::$serviceProduits = new \pizzashop\shop\domain\service\catalogue\ServiceCatalogue();
        self::$serviceCommande = new \pizzashop\shop\domain\service\commande\ServiceCommande(self::$serviceProduits);
        self::$faker = Factory::create('fr_FR');
        self::fill();

    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
        self::cleanDB();
    }


    private static function cleanDB(){
        foreach (self::$commandeIds as $id){
            Commande::find($id)->delete();
        }
        foreach (self::$itemIds as $id){
            Item::find($id)->delete();
        }
    }
    private static function fill() {

   	 	// TODO : créer une commande dans la base pour tester l'accès à une commande
    }


    public function testAccederCommande(){
        //$id = self::$commandeIds[0];
        foreach (self::$commandeIds as $id){
            $commandeEntity = Commande::find($id);
            $commandeDTO = self::$serviceCommande->accederCommande($id);
            $this->assertNotNull($commandeDTO);
 
            // TODO : comparer les données de l'entité et du DTO
        }


    }

}