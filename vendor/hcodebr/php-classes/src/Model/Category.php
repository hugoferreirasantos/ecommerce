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

	}

	//Fim: Método Delete()



	 

}




?>