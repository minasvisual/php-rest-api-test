<?php
error_reporting(E_ALL); 
  
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../lib/database.php";
require_once __DIR__ . "/../lib/rest.php";

$config = include_once __DIR__ . "/../config.php";

function exception_handler($exception) { 
  header('Content-type: application/json');
  http_response_code( $exception->getCode() ?? 500 );
  echo json_encode([ 'code' => $exception->getCode() ?? 500,  'message' => $exception->getMessage() ]);
}

set_exception_handler('exception_handler');


$rest = new Rest();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = str_replace( $config->BASE_URL , '', $uri );

//$uri = explode( '/', $uri );
$requestMethod = $_SERVER["REQUEST_METHOD"];

require_once __DIR__ . '/../src/routes.php';

$rest->processRequest($requestMethod, $uri);