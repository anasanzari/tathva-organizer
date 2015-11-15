<?php

class Location{
        
        const LOCATION_TABLE = "locations";
	const LOCATION_ID = "id";
        const LOCATION_NAME = "place";
	const LOCATION_LATTITUDE = "lattitude";
	const LOCATION_LONGITUDE = "longitude";
	const LOCATION_TYPE = "type";
	

        private $id;
	private $name;
	private $lattitude;
	private $longitude;
        private $type;
        
        
	function __construct($id,$name,$lattitude,$longitude,$type){
                
            $this->id=$id;
            $this->name = $name;
            $this->lattitude = $lattitude;
            $this->longitude = $longitude;
            $this->type = $type;
		
		
	}
        
        public static function initWithArray($array){
            $instance = new self(NULL,NULL,NULL,NULL,NULL);
            $instance->setName($array[Location::LOCATION_NAME]);
            $instance->setId($array[Location::LOCATION_ID]);
            $instance->setLattitude($array[Location::LOCATION_LATTITUDE]);
            $instance->setLongitude($array[Location::LOCATION_LONGITUDE]);
            $instance->setType($array[Location::LOCATION_TYPE]);
            return $instance;
        }
        
       

	static function select($db,$id,$isObject=true){
                $table = self::LOCATION_TABLE;

		$query = "SELECT * FROM `$table` WHERE ".self::LOCATION_ID." = '$id';";
                $objts = self::selectFn($db, $query, $isObject);
                if(sizeof($objts)==0) return NULL;
		else return $objts[0];

	}
        
     
	static function selectAll($db,$isObject=true){
                $table = self::LOCATION_TABLE;
		$query = "SELECT * FROM `$table`;";
		return self::selectFn($db, $query,$isObject);
	}
 
        
        static private function selectFn($db,$query,$isObject){
                $result = $db->query($query);
                if($isObject)
                    $objts = self::getObjectArray($result);
                else
                    $objts = self::getJSONArray($result);
                
                return $objts;
        }
        
        private static function getObjectArray($result){
           
                     $object_array = array();
                     while($row = mysqli_fetch_assoc($result)){
                         $object_array[] = self::initWithArray($row);
                     }
                    return $object_array;
           
        }
        
        private static function getJSONArray($result){

                     $object_array = array();
                     while($row = mysqli_fetch_assoc($result)){
                         $object_array[] = $row;
                     }
                    return $object_array;

        }
        
        
        
        function getId() {
            return $this->id;
        }

        function getName() {
            return $this->name;
        }

        function getLattitude() {
            return $this->lattitude;
        }

        function getLongitude() {
            return $this->longitude;
        }

        function getType() {
            return $this->type;
        }

        function setId($id) {
            $this->id = $id;
        }

        function setName($name) {
            $this->name = $name;
        }

        function setLattitude($lattitude) {
            $this->lattitude = $lattitude;
        }

        function setLongitude($longitude) {
            $this->longitude = $longitude;
        }

        function setType($type) {
            $this->type = $type;
        }




        
        
}

?>