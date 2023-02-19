<?php
//Rota a ver com o ADMIN/USERS:
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;


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


?>