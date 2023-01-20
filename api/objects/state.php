<?php
	class State{	
		public $db;
		public $error;
		
		public function __construct($db, $error){
			$this->db = $db;
			$this->error = $error;
		}
		
		public function read(){
			try{
                $stmt = $this->db->prepare("SELECT * FROM states");
                if($stmt->execute()){
                    $init = [];
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                       array_push($init, array( "id" => $row["state_id"], "name" => $row["state_name"]));
                    }
                    echo json_encode($init);
                    http_response_code(200);
                    return true;
                }
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "server error encountered when creating user");
			}
			return false;
		}

		public function all(){
			$init = [];
			try{
                $stmt = $this->db->prepare("SELECT * FROM states");
                $lga = new LGA($this->db, $this->error);
                if($stmt->execute()){
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    	array_push($init, array( "id" => $row["state_id"], "name" => $row["state_name"], "lgas" => $lga->all($row["state_id"])));
                    }
                }
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "server error encountered when creating user");
			}
			return $init;
		}

        public function get(){
			try{
                $stmt = $this->db->prepare("SELECT * FROM states");
                if($stmt->execute()){
                    $init = [];
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        $lga = new LGA($this->db, $this->error);
                        array_push($init, array( "id" => $row["state_id"], "name" => $row["state_name"], "lgas" => $lga->get($row["state_id"])));
                    }
                    echo json_encode($init);
                    http_response_code(200);
                    return true;
                }
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "server error encountered when creating user");
			}
			return false;
		}

        public function init($state_id){
			try{
                $stmt = $this->db->prepare("SELECT * FROM states WHERE state_id = :state_id");
                $stmt->bindParam(":state_id", $state_id);
                if($stmt->execute() && $row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    return array( "id" => $row["state_id"], "name" => $row["state_name"]);
                }
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "server error encountered when creating user");
			}
			return false;
		}
	}
