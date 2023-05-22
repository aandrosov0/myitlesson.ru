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

define('UPLOAD_DIR', __DIR__ . '/public/images');
define('UPLOAD_COURSE_DIR', UPLOAD_DIR . '/course');
define('DEFAULT_TEMPLATES_DIR', __DIR__ . '/templates');

if(!file_exists(UPLOAD_DIR)) {
	mkdir(UPLOAD_DIR);
}

if(!file_exists(UPLOAD_COURSE_DIR)) {
	mkdir(UPLOAD_COURSE_DIR);
}

$ormConfig = ORMSetup::createAttributeMetadataConfiguration(
	paths: ["src/Entity"],
	isDevMode: $isDevMode,
	proxyDir: __DIR__ . '/tmp'
);

$dbConnection = DriverManager::getConnection($dbParams, $ormConfig);
$entityManager = new EntityManager($dbConnection, $ormConfig);