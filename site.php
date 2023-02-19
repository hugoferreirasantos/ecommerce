<?php
//Rotas a ver com site:

use \Hcode\Page;


$app->get('/', function() {
    
	$page = new Page();

	$page->setTpl("index");

});



?>