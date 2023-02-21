<?php
//Rota a ver com ADMIN/CATEGORIES:
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;
use \Hcode\Model\Product;

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


//Rota para acessar a tela categories-products.html:
$app->get('/admin/categories/:idcategory/products',function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-products",[
		"category"=>$category->getValues(),
		"productsRelated"=>$category->getProducts(),
		"productsNotRelated"=>$category->getProducts(false)
	]);

});

//Rota para adicionar o id do produto na tabela tb_productscategories:
$app->get('/admin/categories/:idcategory/products/:idproduct/add',function($idcategory,$idproduct){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$product = new Product();

	$product->get((int)$idproduct);

	$category->addProduct($product);

	header("Location: /admin/categories/".$idcategory."/products");
	exit;

});

//Rota para remover o id do produto na tabela tb_productcategories:
$app->get('/admin/categories/:idcategory/products/:idproduct/remove',function($idcategory,$idproduct){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$product = new Product();

	$product->get((int)$idproduct);

	$category->removeProduct($product);

	header("Location: /admin/categories/".$idcategory."/products");
	exit;

});


?>