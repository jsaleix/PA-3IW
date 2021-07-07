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

					if($col == 'IS NULL'){
							$columns[$key] = NULL;
						}
					
					if($col == 'IS FALSE'){
						$columns[$key] = 0;
					}
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

					if($col == 'IS NULL'){
							$columns[$key] = NULL;
						}
					
					if($col == 'IS FALSE'){
						$columns[$key] = 0;
					}
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

	public function delete(){
		try{
			$columns = array_diff_key (
							get_object_vars($this),
							get_class_vars(get_class())
						);

			unset($columns["id"]);
			foreach($columns as $key => $col){
				if( empty($col))
					unset($columns[$key]);

				if($col == 'IS NULL'){
						$columns[$key] = NULL;
					}
			}
			$setCmd = [];
			foreach( array_keys($columns) as $field )
			{
				if(!is_null($this->$field) && !empty($this->$field))
				{
					array_push($setCmd, $field . " =:" . $field . "");
				}
			}
			$req = "DELETE FROM " . $this->table . ' WHERE ';
			if(count($setCmd) > 0){
				$req .= implode(' AND ', $setCmd) ;
			}
			if( method_exists($this, "getId") && $this->getId()){
				if(count($setCmd) > 0) $req .= ' AND ';
				$req .= ' id = ' . $this->getId();
			}

			echo $req;
			$query 	= $this->pdo->prepare($req);
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
		/*echo "SELECT * FROM ".$this->table." WHERE " .  implode(" = ? AND ", array_keys($columns)) . " = ? ";
		echo var_dump(array_values($columns));
		echo '<br><br>';*/
		$query = $this->pdo->prepare("SELECT * FROM ".$this->table." WHERE " . 
		implode(" = ? AND ", array_keys($columns)) . " = ? ");

		$query->execute(array_values($columns));
		$result = $query->fetch();	

		return !isset($result[0]) ? false : $result;
	}

	public function findAllLike($groupBy){
		$columns = array_diff_key (
			get_object_vars($this),
			get_class_vars(get_class())
		);
		foreach($columns as $key => $col){
			if( empty($col) || $col === NULL ){
				unset($columns[$key]);
			}else{
				//$columns[$key] = '%'.$columns[$key].'%';
			}
		}

		$req = "SELECT * FROM ".$this->table;

		$index = 0;
		$len = count($columns);
		if( $len > 0) {
			$req .= " WHERE ";
			foreach( $columns as $key => $col ){
				if($col == 'IS NULL' || $col == 'IS NOT NULL'){
					$req .= $key . ' ' .$col ;
					unset($columns[$key]);
				}else{
					$req .= $key . " LIKE CONCAT('%',?,'%') ";
				}
				$index++;
				if($index < $len){
					$req .= ' OR ';
				}
			}
		}

		if($groupBy){
			$req .= " GROUP BY '" . $groupBy . "'";
		}

		$query = $this->pdo->prepare($req);
		$query->execute(array_values($columns));
		$result = $query->fetchAll();

		return !isset($result[0]) ? false : $result;
	}

	public function updateAll(array $setValues, array $whereEquals, array $whereDifferents): bool{
		$req = 'UPDATE '.$this->table;
		
		if(count($setValues) == 0){
			return false;
		}

		foreach($setValues as $field => $value){
			$len = count($setValues);
			$idx = 1;
			$req .= ' SET ' . $field . ' = ' . $value;
			if($idx < $len){
				$req .= ', ';
			}
			$idx++;
		}

		if(count($whereEquals) != 0){
			$len = count($whereEquals);
			$idx = 1;
			$req .= ' WHERE ';
			foreach($whereEquals as $field => $value){
				$req .= $field . ' = ' . $value;
				if($idx < $len){
					$req .= ' AND ';
				}
				$idx++;
			}
		}

		if(count($whereDifferents) != 0){
			$len = count($whereDifferents);
			$idx = 1;
			if(count($whereEquals) == 0){
				$req .= ' WHERE ';
			}else{
				$req .= ' AND ';
			}
			foreach($whereDifferents as $field => $value){
				$req .= $field . ' <> ' . $value;
				if($idx < $len){
					$req .= ' AND ';
				}
				$idx++;
			}
		}

		try{
			$query 	= $this->pdo->prepare($req);
			$query->execute();
			return true;
		}catch(\Exception $e){
			echo $e->getMessage();
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

	public function deleteTables(){
		if(get_class($this) != "App\Models\Site")
			return false;
		try{
			$tables = array(
				$this->prefix."_Menu_dish_association",
				$this->prefix."_Menu",
				$this->prefix."_Dish",
				$this->prefix."_DishCategory",
				$this->prefix."_Comment",
				$this->prefix."_Post_medium_association",
				$this->prefix."_Post",
				$this->prefix."_Medium",
				$this->prefix."_Content",
				$this->prefix."_Page",
				$this->prefix."_Category",
				$this->prefix."_Booking",
			);
			$query = "DROP TABLE IF EXISTS `easymeal`.`";
			foreach($tables as $table){
				$this->pdo->query($query.$table."`;");
			}
			$this->delete();
		} catch(\Exception $e){
			print_r($e);
		}
	}

	public function getLastId(){
		return $this->pdo->lastInsertId();
	}

}






