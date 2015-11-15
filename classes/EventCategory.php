<?php

class EventCategory{
        
        const EVENTCATEGORY_TABLE = "event_cats";
	const EVENTCATEGORY_ID = "cat_id";
        const EVENTCATEGORY_PAR_CAT = "par_cat";
	const EVENTCATEGORY_NAME = "name";

        private $id;
	private $par_cat;
        private $name;
        
	function __construct($id,$name,$par_cat){
            
		$this->name = $name;
		$this->id = $id;
                $this->par_cat = $par_cat;
		
	}
        
        public static function initWithArray($array){
            $instance = new self(NULL,NULL,NULL);
            $instance->setName($array[EventCategory::EVENTCATEGORY_NAME]);
            $instance->setId($array[EventCategory::EVENTCATEGORY_ID]);
            $instance->setPar_cat($array[EventCategory::EVENTCATEGORY_PAR_CAT]);
            
            return $instance;
        }
        
        

	static function insert($db,$code,$name,$cat_id,$short_desc,$long_desc,
                $tags,$contacts,$prize,$prtpnt,$validate){
               
                $table = self::EVENTCATEGORY_TABLE;
                $name = $db->escape($name);
                $code = $db->escape($code);
                $cat_id = $db->escape($cat_id);
                $short_desc= $db->escape($short_desc);
                $long_desc = $db->escape($long_desc);
                $tags = $db->escape($tags);
                $contacts = $db->escape($contacts);
                $prize = $db->escape($prize);
                $prtpnt = $db->escape($prtpnt);
                $validate = $db->escape($validate);
                
                $sql = "INSERT INTO `$table` (`".self::EVENTCATEGORY_CODE."`, `".self::EVENTCATEGORY_NAME."`, "
                        . "`".self::EVENTCATEGORY_CAT_ID."`, `".self::EVENTCATEGORY_SHORTDESC."`, "
                        . "`".self::EVENTCATEGORY_LONGDESC."`, `".self::EVENTCATEGORY_TAGS."`, `".self::EVENTCATEGORY_CONTACTS."`, "
                        . "`".self::EVENTCATEGORY_PRIZE."`, `".self::EVENTCATEGORY_PRTPNT."`, `".self::EVENTCATEGORY_VALIDATE."`) "
                        . "VALUES ('$code', '$name', '$cat_id', '$short_desc', '$long_desc',"
                        . " '$tags', '$contacts', '$prize', '$prtpnt', '$validate')";
                
                return $db->query($sql);
		
	}

	
  

	static function select($db,$code,$isObject=true){
                $table = self::EVENTCATEGORY_TABLE;

		$query = "SELECT * FROM `$table` WHERE ".self::EVENTCATEGORY_ID." = '$code';";
                $objts = self::selectFn($db, $query, $isObject);
                if(sizeof($objts)==0) return NULL;
		else return $objts[0];

	}
        
     
        
	static function selectAll($db,$isObject=true){
                $table = self::EVENTCATEGORY_TABLE;
		$query = "SELECT * FROM `$table`;";
		return self::selectFn($db, $query,$isObject);
	}
        
        static function selectWithParCat($db,$par_cat,$isObject=true){
                $table = self::EVENTCATEGORY_TABLE;
		$query = "SELECT * FROM `$table` WHERE par_cat = '$par_cat';";
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

        function getPar_cat() {
            return $this->par_cat;
        }

        function getName() {
            return $this->name;
        }

        function setId($id) {
            $this->id = $id;
        }

        function setPar_cat($par_cat) {
            $this->par_cat = $par_cat;
        }

        function setName($name) {
            $this->name = $name;
        }





        
        
}

?>