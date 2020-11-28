#!/usr/bin/env php

<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Command\WeatherCommand;
use Dotenv\Dotenv;
use Symfony\Component\Console\Application;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $app = new Application();
    $app->add(new WeatherCommand());
    $app->run();
} catch (Exception $e) {
    echo "[ERROR] Exception occurred during executing the script: {$e->getMessage()}";
    die();
}
