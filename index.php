<?php 

//Start em uma sessão:
session_start();

require_once("vendor/autoload.php");

use \Slim\Slim;

$app = new Slim();

$app->config('debug', true);

//Rotas que tem a ver com o site:
require_once("site.php");

//Rota que tem a ver com funções:
require_once("functions.php");

//Rotas que tem a ver com ADMIN:
require_once("admin.php");

//Rotas que tem a ver com ADMIN/USERS:
require_once("admin-users.php");

//Rotas que tem a ver com ADMIN/CATEGORIES:
require_once("admin-categories.php");

//Rotas que tem a ver com ADMIN/PRODUCTS:
require_once("admin-products.php");





$app->run();

?>