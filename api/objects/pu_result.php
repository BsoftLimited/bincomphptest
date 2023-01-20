<?php
	class PollingUnitResult{
		public $db;
		public $error;
		
		public function __construct($db, $error){
			$this->db = $db;
			$this->error = $error;
		}

        public function get($pu_id){
            $init = [];
			try{
                $stmt = $this->db->prepare("SELECT * FROM announced_pu_results WHERE polling_unit_uniqueid = :polling_unit_uniqueid");
                $stmt->bindParam(":polling_unit_uniqueid", $pu_id);
                if($stmt->execute()){
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        array_push($init, array(
                            "party" => $row["party_abbreviation"],
                            "user" => $row["entered_by_user"],
                            "score" => $row["party_score"],
                            "date" => $row["date_entered"]));
                    }
                }
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "server error encountered when creating user");
			}
			return $init;
		}

        public function init(){
            $init = [];
            $pollings = [];
			try{
                $stmt = $this->db->prepare("SELECT polling_unit_uniqueid FROM announced_pu_results");
                if($stmt->execute()){
                    $polling_unit = new PollingUnit($this->db, $this->error);
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        if(!in_array($row["polling_unit_uniqueid"], $pollings)){
                            array_push($pollings, $row["polling_unit_uniqueid"]);
                        }
                    }
                }

                foreach($pollings as $polling){
                    array_push( $init, array(
                        "results" => $this->pollings( $polling), 
                        "polling" =>  $polling_unit->init( $polling)
                    ));
                }
                echo json_encode($init);
                http_response_code(200);
                return true;
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "server error encountered when creating user");
			}
			return false;
        }

        public function pollings($polling_unit_uniqueid){
            $init = [];
            $parties = [];
			try{
                $stmt = $this->db->prepare("SELECT * FROM announced_pu_results WHERE polling_unit_uniqueid = :polling_unit_uniqueid");
                $stmt->bindParam(":polling_unit_uniqueid", $polling_unit_uniqueid);
                if($stmt->execute()){
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        array_push($init, array(
                            "party" => $row["party_abbreviation"],
                            "user" => $row["entered_by_user"],
                            "score" => $row["party_score"],
                            "date" => $row["date_entered"]));
                        if(!in_array($row["party_abbreviation"], $parties)){
                            array_push($parties, $row["party_abbreviation"]);
                        } 
                    }
                }
			}catch(PDOException $e){
				$this->error->add(500, $e->getMessage(), "server error encountered when creating user");
			}
			return array("unit" => $init, "parties" => $parties);
		}
	}
