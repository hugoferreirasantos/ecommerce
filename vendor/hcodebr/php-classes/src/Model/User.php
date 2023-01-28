<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

//Class User:
class User extends Model {

	//Atributos:
	const SESSION = "User";


	//Métodos:

	 //Inicio: Método login:
	public static function login($login, $password)
	{
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN",array(
			":LOGIN"=>$login
		));

		//Verificar se encontrou algum resultado:
		if(count($results) === 0)
		{
			throw new \Exception("Usuário inexistente ou senha inválida");
		}

		//Caso encontre dados, verifique a senha do usuário:
		$data = $results[0];

		if(password_verify($password, $data["despassword"]) === true)
		{
			$user = new User();

			$user->setData($data);

			//Criar uma sessão:
			$_SESSION[User::SESSION] = $user->getValues();

			return $user;

		} else {

			throw new \Exception("Usuário inexistente ou senha inválida");

		}
	 
	}
	 //Fim: Método login:

	 //Inicio: Método setData:
	public function setData($data = array())
	{

		foreach($data as $key => $value){

			$this->{"set".$key}($value);

		}

	}
	 //Fim: Método setData:

	 //Inicio: Método verifyLogin:
	public static function verifyLogin($inadmin = true)
	{

		if(
			!isset($_SESSION[User::SESSION])
			|| 
			!$_SESSION[User::SESSION]
			||
			!$_SESSION[User::SESSION]["iduser"] > 0
			||
			(bool)$_SESSION[User::SESSION]["iduser"] !== $inadmin
		) {

			header("Location: /admin/login");
			exit;

		}

	}
	 //Fim: Método verifyLogin:

	 //Inicio: Método logout:
	public static function logout()
	{

		$_SESSION[User::SESSION] = NULL;

	}

	 //Fim: Método logout:

	 //Inicio: Método listAll():
	public static function listAll()
	{

		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");

	}

	 //Fim:: Método listAll():

	 //Inicio: Método save:
	public function save()
	{

		$sql = new Sql();

		$results = $sql->select("CALL sp_users_save(:desperson,:deslogin,:despassword,:desemail,:nrphone,:inadmin)",
			array(
			":desperson"=>$this->getdesperson(),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()
		));

		$this->setData($results[0]);


	}
	 //Fim: Método save:

	 //Inicio: Método get():
	public function get($iduser)
	{

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser", 
			array(
				"iduser"=>$iduser
		));

		$this->setData($results[0]);

	}

	 //Fim: Método get();

	 //Inicio: Método update():
	public function update()
	{

		$sql = new Sql();

		$results = $sql->select("CALL sp_usersupdate_save(:iduser,:desperson,:deslogin,:despassword,:desemail,:nrphone,:inadmin)",
			array(
			":iduser"=>$this->getiduser(),
			":desperson"=>$this->getdesperson(),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()
		));

		$this->setData($results[0]);

	}

	 //Fim: Método update():

	 //Inicio: Método delete():

	public function delete()
	{


		$sql = new Sql();

		$sql->query("CALL sp_users_delete(:iduser)",array(
			":iduser"=>$this->getiduser()
		));


	}

	 //Fim: Método delete():

}




?>