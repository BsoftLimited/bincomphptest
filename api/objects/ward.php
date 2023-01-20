<?php
	class Ward{
		public $db;
		public $error;
		
		public function __construct($db, $error){
			$this->db = $db;
			$this->error = $error;
		}
		
		public function read($lga_id){
			try{
                $stmt = $this->db->prepare("SELECT * FROM ward WHERE lga_id = :lga_id");
                $stmt->bindParam(":lga_id", $lga_id);
                if($stmt->execute()){
                    $init = [];
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        array_push($init, array(
                            "uniqueid" => $row["uniqueid"],
                            "id" => $row["ward_id"],
                            "name" => $row["ward_name"],
                            "description" => $row["ward_description"],
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

        public function all($lga_id){
            $init = [];
            try{
                $stmt = $this->db->prepare("SELECT * FROM ward WHERE lga_id = :lga_id");
                $stmt->bindParam(":lga_id", $lga_id);
                if($stmt->execute()){
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        $polling_unit = new PollingUnit($this->db, $this->error);
                        array_push($init, array(
                            "uniqueid" => $row["uniqueid"],
                            "id" => $row["ward_id"],
                            "name" => $row["ward_name"]));
                    }
                }
            }catch(PDOException $e){
                $this->error->add(500, $e->getMessage(), "server error encountered when creating user");
            }
            return $init;
        }

        public function get($lga_id){
            $init = [];
			try{
                $stmt = $this->db->prepare("SELECT * FROM ward WHERE lga_id = :lga_id");
                $stmt->bindParam(":lga_id", $lga_id);
                if($stmt->execute()){
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        $polling_unit = new PollingUnit($this->db, $this->error);
                        array_push($init, array(
                            "uniqueid" => $row["uniqueid"],
                            "id" => $row["ward_id"],
                            "name" => $row["ward_name"],
                            "description" => $row["ward_description"],
                            "user" => $row["entered_by_user"],
                            "date" => $row["date_entered"],
                            "user_ip" => $row["user_ip_address"], "pollings" => $polling_unit->get($lga_id,  $row["ward_id"])));
                    }
                }
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "server error encountered when creating user");
			}
			return $init;
		}

        public function init($ward_id){
			try{
                $stmt = $this->db->prepare("SELECT * FROM ward WHERE ward_id = :ward_id");
                $stmt->bindParam(":ward_id", $ward_id);
                if($stmt->execute() && $row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        return array(
                            "uniqueid" => $row["uniqueid"],
                            "id" => $row["ward_id"],
                            "name" => $row["ward_name"],
                            "description" => $row["ward_description"],
                            "user" => $row["entered_by_user"],
                            "date" => $row["date_entered"],
                            "user_ip" => $row["user_ip_address"]);
                }
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "server error encountered when creating user");
			}
			return $init;
		}
	}
