<?php

namespace pizzashop\logs;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Level;

require_once __DIR__ . '/../vendor/autoload.php';

class CommandeLogger {
    
    private $logFile = __DIR__.'/commande.log';
    
    function __construct(string $message, string $level) 
    {
        $logLevels = [
            'emergency' => Level::Emergency,
            'alert'     => Level::Alert,
            'critical'  => Level::Critical,
            'error'     => Level::Error,
            'warning'   => Level::Warning,
            'notice'    => Level::Notice,
            'info'      => Level::Info,
            'debug'     => Level::Debug,
        ];

        $logger = new Logger('pizza.shop.commande');
        $logger->pushHandler(
            new StreamHandler($this->logFile, $logLevels[$level] ?? Level::Warning)
        );
        $logger->log($logLevels[$level], $message);
    }
}
