<?php
//Rotas a ver com site:

use \Hcode\Page;
use \Hcode\Model\Product;
use \Hcode\Model\Category;
use \Hcode\Model\Cart;


$app->get('/', function() {

	$products = Product::listAll();


    
	$page = new Page();

	$page->setTpl("index",[
		"products"=>Product::checkList($products)
	]);

});

//Rota para a visualização da categoria:
$app->get('/categories/:idcategory',function($idcategory){

	$page = (isset($_GET['page']))? (int)$_GET['page'] : 1;

	$category = new Category();

	$category->get((int)$idcategory);

	$pagination = $category->getProductPage($page);

	$pages = [];

	for ($i=1; $i <= $pagination["pages"] ; $i++) { 
		array_push($pages, [
			'link'=>'/categories/'.$category->getidcategory().'?page='.$i,
			'page'=>$i
		]);
	}

	$page = new Page();

	$page->setTpl("category",[
		"category"=>$category->getValues(),
		"products"=>$pagination["data"],
		"pages"=>$pages
	]);	

});

//Rota da captura de um produto via url:
$app->get('/products/:desurl', function($desurl){

	$product  = new Product();

	$product->getFromURL($desurl);

	$page = new Page();

	$page->setTpl("product-detail",[
		"product" =>$product->getValues(),
		"categories"=>$product->getCategories()
	]);

});

//Rota do carrinho de compras:
$app->get('/cart', function(){

	$cart = Cart::getFromSession();

	$page = new Page();

	$page->setTpl("cart",[
		"cart"=>$cart->getValues(),
		"products"=>$cart->getProducts()
	]);

});

//Rota de adição de produtos no carrinho:
$app->get('/cart/:idproduct/add', function($idproduct){

	$product = new Product();

	$product->get((int) $idproduct);

	$cart = Cart::getFromSession();

	$cart->addProduct($product);

	header("Location: /cart");
	exit;

});

//Rota de remoção de um produto no carrinho:
$app->get('/cart/:idproduct/minus', function($idproduct){

	$product = new Product();

	$product->get((int) $idproduct);

	$cart = Cart::getFromSession();

	$cart->removeProduct($product);

	header("Location: /cart");
	exit;

});

//Rota de remoçao de produtos no carrinho:
$app->get('/cart/:idproduct/remove', function($idproduct){

	$product = new Product();

	$product->get((int) $idproduct);

	$cart = Cart::getFromSession();

	$cart->removeProduct($product, true);

	header("Location: /cart");
	exit;

});





?>