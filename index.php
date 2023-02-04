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

	//Método estatico responsavel por verificar o usuário esta logado:
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

	//echo password_hash('admin',PASSWORD_DEFAULT);



});

//Rota para o logout:
$app->get('/admin/logout', function(){

	User::logout();

	header("Location: /admin/login");
	exit;

});

//Rota  tela que vai listar todos os usuários:
$app->get('/admin/users', function(){

	//Método estatico responsavel por verificar o usuário esta logado:
	User::verifyLogin();

	$users = User::listAll();

	$page = new PageAdmin();

	$page->setTpl("users", array(
		"users"=>$users
	));

});

 //incio: CRUD:

//Rota para criar usuários:
$app->get('/admin/users/create', function(){

	//Método estatico responsavel por verificar o usuário esta logado:
	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("users-create");

});

//Rota para exclusão de dados no banco:
$app->get("/admin/users/:iduser/delete", function($iduser){

	//Método estatico responsavel por verificar o usuário esta logado:
	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;	

});

//Rota para dar um update:
$app->get("/admin/users/:iduser", function($iduser){

	//Método estatico responsavel por verificar o usuário esta logado:
	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$page = new PageAdmin();

	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));

});



//Rota para salvar os dados alterados para o banco de dados:
$app->post("/admin/users/create",function(){

	//Método estatico responsavel por verificar o usuário esta logado:
	User::verifyLogin();

	//var_dump($_POST);

	//Criar um usuário novo no banco de dados:
	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->setData($_POST);

	$user->save();

	//var_dump($user);

	//Retornar para a tela de usuarios cadastrados:
	header("Location: /admin/users");
	exit;



});

//Rota para salvar a edição de usuário: UPDATE:
$app->post("/admin/users/:iduser", function($iduser){

	//Método estatico responsavel por verificar o usuário esta logado:
	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");
	exit;


});


 //fim: CRUD

//Rota de esqueci a senha:
$app->get('/admin/forgot',function(){

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot");


});

//Rota para pegar as informações do arquivo forgot.html
$app->post('/admin/forgot',function(){

	
	$user = User::getForgot($_POST["email"]);

	//Depois mandar o usuário para uma rota:
	header("Location: /admin/forgot/send");
	exit;

});

//Rota de envio de email:
$app->get('/admin/forgot/send', function(){


	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot-sent");


});

 //Inicio: Rota para redefinir a senha:
$app->get('/admin/forgot/reset',function(){

	//Processo de validação do usuário:
	$user = User::validForgotDecrypt($_GET["code"]);

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);


	$page->setTpl("forgot-reset",array(
		"name"=>$user["desperson"],
		"code"=>$_GET["code"]
	));



});

$app->post('/admin/forgot/reset', function(){

	$forgot = User::validForgotDecrypt($_POST["code"]);

	//Método de falar que esse processo de recuperação já foi usado:
	User::setForgotUsed($forgot["idrecovery"]);

	$user = new User();

	$user->get((int)$forgot["iduser"]);

	//Criptografar a senha para inseri-la no banco:
	$password = password_hash($_POST["password"], PASSWORD_DEFAULT, [
		"cost"=>12
	]);

	$user->setPassword($password);

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);


	$page->setTpl("forgot-reset-success");



});

 //Fim: Rota para redefinir a senha:


$app->run();

 ?>