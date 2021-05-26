<?php

namespace App\Core;

class Database
{

	private $pdo;
	private $table;
	private $rawPrefix; 

	public function __construct($tablePrefix = null){
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
		/*if($tablePrefix){
			$this->table = $tablePrefix.end($getCalledClassExploded);
			$this->rawPrefix = str_replace('_','', $tablePrefix);

		}else{
		}*/
		$this->table = DBPREFIXE.end($getCalledClassExploded);

	}

	protected function setTableName($prefix){
		$getCalledClassExploded = explode("\\", get_called_class()); //App\Models\User
		$this->table = $prefix.end($getCalledClassExploded);
		$this->rawPrefix = str_replace('_','', $prefix);
	}

	protected function getTableName(){
		return $this->table;
	}

	protected function getPrefix(){
		return $this->rawPrefix;
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
				foreach($columns as $key => $col){
					if( empty($col))
						unset($columns[$key]);
				}
				$query = $this->pdo->prepare("INSERT INTO ".$this->table." (".
						implode(",", array_keys($columns))
					.") 
					VALUES ( :".
						implode(",:", array_keys($columns))
					." );");	
			}else{
				//UPDATE
				unset($columns["id"]);
				foreach($columns as $key => $col){
					if( empty($col))
						unset($columns[$key]);
				}
				$setCmd = [];
				foreach( array_keys($columns) as $field )
				{
					if(!is_null($this->$field) && !empty($this->$field))
					{
						array_push($setCmd, $field . " = :" . $field . "");
					}
				}
				$req 	= "UPDATE " . $this->table . " SET " . implode(', ', $setCmd) . ' WHERE id = ' . $this->getId();
				$query 	= $this->pdo->prepare($req);
			}
			$query->execute($columns);
			return true;
		}catch(\Exception $e){
			echo $e->getMessage();
			return false;
		}
	}

	public function findAll(){
		$columns = array_diff_key (
			get_object_vars($this),
			get_class_vars(get_class())
		);
		foreach($columns as $key => $col){
			if( empty($col) || $col === NULL )
				unset($columns[$key]);

			if($col == 'NULL')
				$columns[$key] = 'IS NULL';
		}

		$req = "SELECT * FROM ".$this->table;
		/*if(count($columns) > 0) {
			$req .= " WHERE " . implode(" = ? AND ", array_keys($columns)) . " = ? ";
		}*/
		$index = 0;
		$len = count($columns);
		if( $len > 0) {
			$req .= " WHERE ";
			foreach( $columns as $key => $col ){
				if($col == 'IS NULL' || $col == 'IS NOT NULL'){
					$req .= $key . ' ' .$col ;
					unset($columns[$key]);
				}else{
					$req .= $key . ' = ? ';
				}
				$index++;
				if($index < $len){
					$req .= ' AND ';
				}
			}
		}
		$query = $this->pdo->prepare($req);
		$query->execute(array_values($columns));
		$result = $query->fetchAll();

		return !isset($result[0]) ? false : $result;
	}
	
	public function findOne(){
		$columns = array_diff_key (
			get_object_vars($this),
			get_class_vars(get_class())
		);
		foreach($columns as $key => $col){
			if( empty($col) || $col === NULL )
				unset($columns[$key]);
		}

		$query = $this->pdo->prepare("SELECT * FROM ".$this->table." WHERE " . 
		implode(" = ? AND ", array_keys($columns)) . " = ? ");

		$query->execute(array_values($columns));
		$result = $query->fetch();	
		/*echo "SELECT * FROM ".$this->table." WHERE " . implode(" = ? AND ", array_keys($columns)) . " = ? <br>";
		echo implode('-', $columns) . '<br><br>';
		var_dump($result);
		echo '<br><br>';*/
		return !isset($result[0]) ? false : $result;
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






