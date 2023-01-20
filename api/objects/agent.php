<?php
	class Agent{
		public $db;
		public $error;
		
		public function __construct($db, $error){
			$this->db = $db;
			$this->error = $error;
		}
		
		public function read($state_id){
			try{
                $stmt = $this->db->prepare("SELECT * FROM agentname");
                if($stmt->execute() && $row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    http_response_code(200);
                    echo json_encode(
                        array(  "id" => $row["name_id"],
                                "first" => $row["firstname"],
								"last" => $row["lastname"],
								"email" => $row["email"],
								"phone" => $row["phone"]
                                "polling_unit_id" => $row["polling_unit_id"]));
                }
                return true;
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "server error encountered when creating user");
			}
			return false;
		}
	}
