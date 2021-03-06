<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/app/models/"), $isDevMode);
// or if you prefer yaml or XML
//$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

// database configuration parameters
$conn = array(
  'dbname' => 'database',
  'user' => 'user',
  'password' => 'password',
  'host' => 'host',
  'driver' => 'pdo_mysql',
  // 'unix_socket' => "/Applications/MAMP/tmp/mysql/mysql.sock"
);

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);
