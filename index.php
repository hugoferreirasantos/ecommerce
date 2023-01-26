<?php 

//Start em uma sessão:
session_start();

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	$page = new Page();

	$page->setTpl("index");

});

//Rota da pagina do admin:
$app->get('/admin',function(){

	//Método estatico responsavel por verificar o usuário:
	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("index");

});

//Rota para o login:
$app->get('/admin/login', function(){

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("login");

});

//Rota de validação de login:
$app->post('/admin/login', function(){

	User::login($_POST["login"], $_POST["password"]);

	header("Location: /admin");
	exit;

});

//Rota para o logout:
$app->get('/admin/logout', function(){

	User::logout();

	header("Location: /admin/login");
	exit;

});

$app->run();

 ?>