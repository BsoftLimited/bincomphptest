<?php
	class Party{
		public $db;
		public $error;
		
		public function __construct($db, $error){
			$this->db = $db;
			$this->error = $error;
		}
		
		public function get(){
			try{
                $stmt = $this->db->prepare("SELECT * FROM party");
                if($stmt->execute() && $row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    http_response_code(200);
                    echo json_encode(
                        array(  "id" => $row["id"],
                                "partyid" => $row["partyid"],
                                "name" => $row["partyname"]));
                }
                return true;
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "server error encountered when creating user");
			}
			return false;
		}

		public function all(){
			$init = [];
			try{
                $stmt = $this->db->prepare("SELECT * FROM party");
                if($stmt->execute()){
                	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        array_push($init, array(  "id" => $row["partyid"], "name" => $row["partyname"]));
                	}
                }
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "server error encountered when creating user");
			}
			return $init;
		}
	}
