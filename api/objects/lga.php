<?php
	class LGA{
		public $db;
		public $error;
		
		public function __construct($db, $error){
			$this->db = $db;
			$this->error = $error;
		}
		
		public function read($state_id){
			try{
                $stmt = $this->db->prepare("SELECT * FROM lga WHERE state_id = :state_id");
                $stmt->bindParam(":state_id", $state_id);
                if($stmt->execute()){
                    $init = [];
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        array_push($init, array(  
                            "uniqueid" => $row["uniqueid"],
                            "id" => $row["lga_id"],
                            "name" => $row["lga_name"],
                            "description" => $row["lga_description"],
                            "user" => $row["entered_by_user"],
                            "date" => $row["date_entered"],
                            "user_ip" => $row["user_ip_address"]));
                    }
                    http_response_code(200);
                    echo json_encode($init);
                }
                return true;
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "server error encountered when creating user");
			}
			return false;
		}

        public function all($state_id){
            $init = [];
            try{
                $stmt = $this->db->prepare("SELECT * FROM lga WHERE state_id = :state_id");
                $stmt->bindParam(":state_id", $state_id);
                if($stmt->execute()){
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        $ward = new Ward($this->db, $this->error);
                        array_push($init, array(  
                            "uniqueid" => $row["uniqueid"],
                            "id" => $row["lga_id"],
                            "name" => $row["lga_name"],
                            "wards" => $ward->all( $row["lga_id"])));
                    }
                }
            }catch(PDOException $e){
                $this->error->add(500, $e->getMessage(), "server error encountered when creating user");
            }
            return $init;
        }

        public function get($state_id){
            $init = [];
			try{
                $stmt = $this->db->prepare("SELECT * FROM lga WHERE state_id = :state_id");
                $stmt->bindParam(":state_id", $state_id);
                if($stmt->execute()){
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        $ward = new Ward($this->db, $this->error);
                        array_push($init, array(  
                            "uniqueid" => $row["uniqueid"],
                            "id" => $row["lga_id"],
                            "name" => $row["lga_name"],
                            "description" => $row["lga_description"],
                            "user" => $row["entered_by_user"],
                            "date" => $row["date_entered"],
                            "user_ip" => $row["user_ip_address"], "wards" => $ward->get( $row["lga_id"])));
                    }
                }
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "server error encountered when creating user");
			}
			return $init;
		}

        public function init($lga_id){
            $init = [];
			try{
                $stmt = $this->db->prepare("SELECT * FROM lga WHERE lga_id = :lga_id");
                $stmt->bindParam(":lga_id", $lga_id);
                if($stmt->execute() && $row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $state = new State($this->db, $this->error);
                    return array(  
                        "uniqueid" => $row["uniqueid"],
                        "id" => $row["lga_id"],
                        "name" => $row["lga_name"],
                        "description" => $row["lga_description"],
                        "user" => $row["entered_by_user"],
                        "date" => $row["date_entered"],
                        "user_ip" => $row["user_ip_address"], "state" => $state->init( $row["state_id"]));
                }
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "server error encountered when creating user");
			}
		}
	}
