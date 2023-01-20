<?php
	class PollingUnit{
		public $db;
		public $error;
		
		public function __construct($db, $error){
			$this->db = $db;
			$this->error = $error;
		}
		
		public function read($lga_id, $ward_id){
			try{
                $stmt = $this->db->prepare("SELECT * FROM polling_unit WHERE lga_id = :lga_id AND ward_id = :ward_id");
                $stmt->bindParam(":lga_id", $lga_id);
                $stmt->bindParam(":ward_id", $ward_id);
                if($stmt->execute()){
                    $init = [];
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        array_push($init, array(
                            "uniqueid" => $row["uniqueid"],
                            "id" => $row["polling_unit_id"],
                            "number" => $row["polling_unit_number"],
                            "name" => $row["polling_unit_name"],
                            "description" => $row["lga_description"],
                            "lat" => $row["lat"],
                            "long" => $row["long"]));
                    }
                    http_response_code(200);
                    echo json_encode($init);
                    return true;
                }
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "server error encountered when creating user");
			}
			return false;
		}

        public function get($lga_id, $ward_id){
            $init = [];
			try{
                $stmt = $this->db->prepare("SELECT * FROM polling_unit WHERE lga_id = :lga_id AND ward_id = :ward_id");
                $stmt->bindParam(":lga_id", $lga_id);
                $stmt->bindParam(":ward_id", $ward_id);
                if($stmt->execute()){
                    $results = new PollingUnitResult($this->db, $this->error);
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        array_push($init, array(
                            "uniqueid" => $row["uniqueid"],
                            "id" => $row["polling_unit_id"],
                            "number" => $row["polling_unit_number"],
                            "name" => $row["polling_unit_name"],
                            "description" => $row["polling_unit_description"],
                            "lat" => $row["lat"],
                            "long" => $row["long"], "results" => $results->get( $row["uniqueid"])));
                    }
                }
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "server error encountered when creating user");
			}
			return $init;
		}

        public function init($uniqueid){
			try{
                $stmt = $this->db->prepare("SELECT * FROM polling_unit WHERE uniqueid = :uniqueid");
                $stmt->bindParam(":uniqueid", $uniqueid);
                if($stmt->execute() && $row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $ward = new Ward($this->db, $this->error);
                    $lga = new LGA($this->db, $this->error);
                    return array(
                        "uniqueid" => $row["uniqueid"],
                        "id" => $row["polling_unit_id"],
                        "number" => $row["polling_unit_number"],
                        "name" => $row["polling_unit_name"],
                        "description" => $row["polling_unit_description"],
                        "lat" => $row["lat"],
                        "long" => $row["long"], 
                        "ward" => $ward->init($row["ward_id"]),
                         "lga" => $lga->init($row["lga_id"]));
                }
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "server error encountered when creating user");
			}
		}
	}
