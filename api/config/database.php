<?php
	class Database {
		private $conn;
		private $error;
		public function __construct($error){
			$this->conn = null; 
			$host = "localhost"; 
			$username = "root";
			$password = "rustup";
			$this->error = $error;
			
			try{
				$this->conn = new PDO("mysql:host=".$host, $username, $password);
				$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->conn->exec("USE bincomphptest;");
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "Database Error");
			}
			return $this->conn;
		}
		
		public function getCommunication(){ return $this->conn; }
		public function checkTable($name){
			try{
				$query = "SELECT * FROM information_schema.tables
						WHERE
							table_schema = :dbname AND table_name = :table";
				$stmt = $this->conn->prepare($query);
				
				$db_name = $this->useHome ? $this->home_db_name : $this->sub_db_name;
				
				$stmt->bindParam(":dbname", $db_name);
				$stmt->bindParam(":table", $name);
				if($stmt->execute() && $stmt->rowCount() > 0){
					return 1;
				}else{
					return 0;
				}
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "database error");
			}
			return 2;
		}
		
		public function createTable($query){
			try{
				$stmt = $this->conn->prepare($query);
				if($stmt->execute()){ return true; }
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "server error");
				echo $query;
			}
			return false;
		}
		
		public function prepare($query){
			try{
				return $this->conn->prepare($query);
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "server communication error");
			}
			return null;
		}
	}
