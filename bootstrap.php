<?php
namespace App;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once "vendor/autoload.php";
require_once "config/config.php";
require_once "src/functions.php";

define("CONFIG_DIR", __DIR__ . '/config');
define("PUBLIC_DIR", __DIR__ . '/public');
define("SRC_DIR", __DIR__ . '/src');
define("IMAGE_DIR", PUBLIC_DIR . '/images');

if(!file_exists(PUBLIC_DIR)) {
	mkdir(PUBLIC_DIR);
}

if(!file_exists(IMAGE_DIR)) {
	mkdir(IMAGE_DIR);
}

$ormConfig = ORMSetup::createAttributeMetadataConfiguration(
	paths: ["src/Entity"],
	isDevMode: $isDevMode,
	proxyDir: __DIR__ . '/tmp'
);

$dbConnection = DriverManager::getConnection($dbParams, $ormConfig);
$entityManager = new EntityManager($dbConnection, $ormConfig);