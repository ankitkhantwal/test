<?php

namespace App\Core;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;

class Logger {
    private static ?Logger $instance = null;
    private MonologLogger $logger;

    private function __construct() {
        $this->logger = new MonologLogger('app');
        
        // Add rotating file handler
        $this->logger->pushHandler(new RotatingFileHandler(
            __DIR__ . '/../../logs/app.log',
            7, // Keep 7 days of logs
            MonologLogger::DEBUG
        ));
    }

    public static function getInstance(): Logger {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function info(string $message, array $context = []): void {
        $this->logger->info($message, $context);
    }

    public function error(string $message, array $context = []): void {
        $this->logger->error($message, $context);
    }

    public function warning(string $message, array $context = []): void {
        $this->logger->warning($message, $context);
    }

    public function debug(string $message, array $context = []): void {
        $this->logger->debug($message, $context);
    }

    private function __clone() {}
    public function __wakeup() {
        throw new \Exception("Cannot unserialize singleton");
    }
} 