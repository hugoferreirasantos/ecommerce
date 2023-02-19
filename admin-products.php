<?php
//Rota a ver com ADMIN/PRODUCTS:
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;
use \Hcode\Model\Product;

$app->get('/admin/products', function(){

	User::verifyLogin();

	$products =  Product::listAll();

	$page = new PageAdmin();

	$page->setTpl("products",[
		"products"=>$products
	]);

});

//Rota para cadastro de produtos:
$app->get('/admin/products/create',function(){

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("products-create");

});

$app->post('/admin/products/create',function(){

	User::verifyLogin();

	$product = new Product();

	$product->setData($_POST);

	$product->save();

	header("Location: /admin/products");
	exit;

});

//Rota para editar o produto:
$app->get('/admin/products/:idproduct', function($idproduct){

	User::verifyLogin();

	$product = new Product();

	$product->get((int)$idproduct);



	$page = new PageAdmin();

	$page->setTpl("products-update",[
		"product"=>$product->getValues()
	]);

});

$app->post('/admin/products/:idproduct', function($idproduct){

	User::verifyLogin();

	$product = new Product();

	$product->get((int)$idproduct);

	$product->setData($_POST);

	$product->save();


	//var_dump($_FILES["name"]);
	//exit;

	$product->setPhoto($_FILES["file"]);

	header("Location: /admin/products");
	exit;


});

//Rota para exluir um produto:
$app->get('/admin/products/:idproduct/delete', function($idproduct){

	User::verifyLogin();

	$product = new Product();

	$product->get((int)$idproduct);

	$product->delete();

	header("Location: /admin/products");
	exit;

});






?>