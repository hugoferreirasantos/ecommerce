<?php


namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;



//Class User:
class Category extends Model {

	

	 //Inicio: Método listAll():
	public static function listAll()
	{

		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_categories ORDER BY descategory");

	}
	 //Fim:: Método listAll():

	//Inicio: Método Save();
	public function save()
	{

		$sql = new Sql();

		$results = $sql->select("CALL sp_categories_save(:idcategory,:descategory)",
			array(
			":idcategory"=>$this->getidcategory(),
			":descategory"=>$this->getdescategory(),
		));

		$this->setData($results[0]);

		Category::updateFile();

	}

	//Fim: Método Save();

	//Inicio: Método Get():
	public function get($idcategory)
	{

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_categories WHERE idcategory = :idcategory",array(
			":idcategory"=>$idcategory
		));

		$this->setData($results[0]);

	}

	//Fim: Método Get();

	//Inicio: Método Delete();
	public function delete()
	{

		$sql = new Sql();

		$sql->query("DELETE FROM tb_categories WHERE idcategory = :idcategory",array(
			":idcategory"=>$this->getidcategory()
		));

		Category::updateFile();

	}

	//Fim: Método Delete()

	//Inicio: Método para atualizar o arquivo:
	public function updateFile()
	{
		//Pegando tudo que esta no banco de dados das categorias:
		$categories = Category::listAll();

		//Incrementando dinâmicamente o array:
		$html = [];

		foreach($categories as $row){
			array_push($html,'<li><a href="/categories/'.$row["idcategory"].'">'.$row["descategory"].'</a></li>');
		}

		file_put_contents($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR."categories-menu.html", implode('', $html));

	}

	//Fim: Método para atualizar o arquivo:



	 

}




?>