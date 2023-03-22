<?php
namespace App;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once "vendor/autoload.php";
require_once "config/config.php";
require_once "functions.php";

define("CONFIG_DIR", __DIR__ . '/config');
define("PUBLIC_DIR", __DIR__ . '/public');
define("SRC_DIR", __DIR__ . '/src');

$ormConfig = ORMSetup::createAttributeMetadataConfiguration(
	paths: ["src/Entity"],
	isDevMode: $isDevMode
);

$dbConnection = DriverManager::getConnection($dbParams, $ormConfig);
$entityManager = new EntityManager($dbConnection, $ormConfig);