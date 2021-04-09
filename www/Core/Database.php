<?php

namespace App\Core;

class Database
{

	private $pdo;
	private $table;

	public function __construct(){
		try{
			$this->pdo = new \PDO(DBDRIVER.":dbname=".DBNAME.";host=".DBHOST.";port=".DBPORT,DBUSER,DBPWD);
			//$this->pdo = new \PDO(DBDRIVER.":dbname=".DBNAME.";host=".'51.178.52.245'.";port=".DBPORT,'myopens-remote','G3n3sis2%');
			//$this->pdo = new \PDO('mysql:host=myopens.fr;dbname=myopens', 'myopens-remote', 'G3n3sis2%', array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));

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
	}

	public function insert($table, array $values){
		$query = $this->pdo->prepare("INSERT INTO ".$table." (".
		implode(",", array_keys($values))
		.") 
		VALUES ( :".
			implode(",:", $values)
		." );");
		$query->execute($columns);

	}

}






