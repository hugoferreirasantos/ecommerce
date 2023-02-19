<?php
//Rota a ver com ADMIN/CATEGORIES:
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;

/*Rota para assesar o template de categorias*/
//Rota que vai trazer uma lista das categorias:
$app->get('/admin/categories',function(){

	User::verifyLogin();

	$categories = Category::listAll();

	$page = new PageAdmin();


	$page->setTpl("categories",[
		'categories'=>$categories
	]);

});

 //Rota para criar categorias:

$app->get('/admin/categories/create',function(){

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("categories-create");

});

$app->post('/admin/categories/create',function(){

	User::verifyLogin();

	$category = new Category();

	$category->setData($_POST);

	$category->save();

	header("Location: /admin/categories");
	exit;

});

//Rota para deletar uma categoria:
$app->get('/admin/categories/:idcategory/delete',function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$category->delete();

	header("Location: /admin/categories");
	exit;

});


//Rota para editar as categorias:
$app->get('/admin/categories/:idcategory',function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-update",[
		"category"=>$category->getValues()
	]);

});

$app->post('/admin/categories/:idcategory',function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	
	$category->setData($_POST);

	$category->save();

	header("Location: /admin/categories");
	exit;

});

$app->get('/categories/:idcategory',function($idcategory){

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new Page();

	$page->setTpl("category",[
		"category"=>$category->getValues(),
		"products"=>[]
	]);	

});



?>