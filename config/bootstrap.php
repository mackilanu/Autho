<?php

require_once '../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Db;

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

$db = new Db;

$db->addConnection([
    "driver" => getenv('DRIVER'),
    "host" => getenv('HOST'),
    "database" => getenv('DB'),
    "username" => getenv('USER'),
    "password" => getenv('PASS'),
]);

$db->setAsGlobal();
$db->bootEloquent();