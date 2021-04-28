<?php

namespace App\Core;

class Database
{

	private $pdo;
	private $table;

	public function __construct(){
		try{
			$this->pdo = new \PDO(DBDRIVER.":dbname=".DBNAME.";host=".DBHOST.";port=".DBPORT,DBUSER,DBPWD);

			if(ENV == "dev"){
				$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	    		$this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
    		}

		}catch(\Exception $e){
			die("Erreur SQL " . $e->getMessage());
		}

		$getCalledClassExploded = explode("\\", get_called_class()); //App\Models\User
		$this->table = DBPREFIXE.end($getCalledClassExploded);
	}


	public function save(){
		try{
			$columns = array_diff_key (
							get_object_vars($this),
							get_class_vars(get_class())
						);

			//INSERT OU UPDATE
			if( is_null($this->getId()) ){
				//INSERT
				$query = $this->pdo->prepare("INSERT INTO ".$this->table." (".
						implode(",", array_keys($columns))
					.") 
					VALUES ( :".
						implode(",:", array_keys($columns))
					." );");	
			}else{
				//UPDATE
				$setCmd = [];
				foreach( array_keys($columns) as $field )
				{
					if(!is_null($this->$field) && !empty($this->$field))
					{
						array_push($setCmd, $field . " = '" . $this->$field . "'");
					}
				}
				$req 	= "UPDATE " . $this->table . " SET " . implode(', ', $setCmd) . ' WHERE id = ' . $this->getId() ;
				$query 	= $this->pdo->prepare($req);
			}
			$query->execute($columns);
			return true;

		}catch(\Exception $e){
			echo $e->getMessage();
			return false;
		}


	}

	public function insert($table, array $values){
		try{
			$query = $this->pdo->prepare("INSERT INTO ".$table." (".
				implode(",", array_keys($values))
				.") 
				VALUES ( :".
					implode(",:", $values)
				." );");
			$query->execute($columns);
			return $query;
		}catch(\Exception $e){
			return false;
		}
	}

	public function createTable($req){
		try{
			$query = $this->pdo->prepare($req);
			$req = $query->execute();
			return $req;
		}catch(\Exception $e){
			echo $e->getMessage();
			return false;
		}
	}

}






