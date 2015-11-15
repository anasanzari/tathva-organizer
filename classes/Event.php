<?php

class Event{
        
        const EVENT_TABLE = "events";
	const EVENT_NAME = "name";
        const EVENT_CODE = "code";
	const EVENT_CAT_ID = "cat_id";
	const EVENT_SHORTDESC = "shortdesc";
	const EVENT_LONGDESC = "longdesc";
	const EVENT_TAGS = "tags";
	const EVENT_CONTACTS = "contacts";
	const EVENT_PRIZE = "prize";
	const EVENT_PRTPNT = "prtpnt";
        const EVENT_VALIDATE = "validate";
        const EVENT_LOCATIONID = "location_id";
        const EVENT_TIMINGS = "timings";

        private $code;
	private $name;
	private $cat_id;
	private $short_desc;
        private $long_desc;
        private $tags;
        private $contacts;
        private $prize;
        private $prtpnt;
        private $validate;
        private $location_id;
        private $timings;
        
	function __construct($code,$name,$cat_id,$short_desc,$long_desc,
                $tags,$contacts,$prize,$prtpnt,$validate,$location_id,$timings){
            
		$this->name = $name;
		$this->code = $code;
                $this->cat_id = $cat_id;
                $this->validate = $validate;
                $this->short_desc = $short_desc;
                $this->long_desc = $long_desc;
                $this->tags = $tags;
                $this->contacts = $contacts;
                $this->prize = $prize;
                $this->prtpnt = $prtpnt;
                $this->location_id = $location_id;
                $this->timings = $timings;
		
	}
        
        public static function initWithArray($array){
            $instance = new self(NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
            $instance->setName($array[Event::EVENT_NAME]);
            $instance->setCode($array[Event::EVENT_CODE]);
            $instance->setCat_id($array[Event::EVENT_CAT_ID]);
            $instance->setShort_desc($array[Event::EVENT_SHORTDESC]);
            $instance->setLong_desc($array[Event::EVENT_LONGDESC]);
            $instance->setTags($array[Event::EVENT_TAGS]);
            $instance->setContacts($array[Event::EVENT_CONTACTS]);
            $instance->setPrize($array[Event::EVENT_PRIZE]);
            $instance->setPrtpnt($array[Event::EVENT_PRTPNT]);
            $instance->setValidate($array[Event::EVENT_VALIDATE]);
            $instance->setLocation_id($array[Event::EVENT_LOCATIONID]);
            $instance->setTimings($array[Event::EVENT_TIMINGS]);
            return $instance;
        }
        
        

	static function insert($db,$code,$name,$cat_id,$short_desc,$long_desc,
                $tags,$contacts,$prize,$prtpnt,$validate,$location_id,$timings){
               
                $table = self::EVENT_TABLE;
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
                $location_id = $db->escape($location_id);
                $timings = $db->escape($timings);
                
                $sql = "INSERT INTO `$table` (`".self::EVENT_CODE."`, `".self::EVENT_NAME."`, "
                        . "`".self::EVENT_CAT_ID."`, `".self::EVENT_SHORTDESC."`, "
                        . "`".self::EVENT_LONGDESC."`, `".self::EVENT_TAGS."`, `".self::EVENT_CONTACTS."`, "
                        . "`".self::EVENT_PRIZE."`, `".self::EVENT_PRTPNT."`, "
                        . "`".self::EVENT_LOCATIONID."`, `".self::EVENT_TIMINGS."`, `".self::EVENT_VALIDATE."`) "
                        . "VALUES ('$code', '$name', '$cat_id', '$short_desc', '$long_desc',"
                        . " '$tags', '$contacts', '$prize', '$prtpnt', '$location_id', '$timings', '$validate')";
                
                return $db->query($sql);
		
	}
        static function updateColumn($db,$id,$column,$val){
            $table = self::EVENT_TABLE; 
            $a = self::EVENT_CODE;
            $column = $db->escape($column);
            $id = $db->escape($id);
            $val = $db->escape($val);
            return $db->query("UPDATE `$table` SET `$column` = '$val' WHERE `$a` = '$id'");     
        }
	
      
	static function delete($db,$code){
		
		$table = self::EVENT_TABLE;
		
		$query = "DELETE FROM $table WHERE ".self::EVENT_CODE." = '$code';";
		return $db->query($query);
	}

	static function select($db,$code,$isObject=true){
                $table = self::EVENT_TABLE;

		$query = "SELECT * FROM `$table` WHERE ".self::EVENT_CODE." = '$code';";
                $objts = self::selectFn($db, $query, $isObject);
                if(sizeof($objts)==0) return NULL;
		else return $objts[0];

	}
        
        static function selectShort($db,$code,$isObject=true){
                $table = self::EVENT_TABLE;
		$query = "SELECT ".self::EVENT_CODE.",".self::EVENT_NAME.",".self::EVENT_SHORTDESC
                        . " FROM `$table` WHERE ".self::EVENT_CODE." = '$code';";
                $objts = self::selectFn($db, $query, $isObject);
                if(sizeof($objts)==0) return NULL;
		else return $objts[0];
        }
        
	static function selectAll($db,$isObject=true){
                $table = self::EVENT_TABLE;
		$query = "SELECT * FROM `$table`;";
		return self::selectFn($db, $query,$isObject);
	}
        
        static function selectAllShort($db){
                $table = self::EVENT_TABLE;
		$query = "SELECT ".self::EVENT_CODE.",".self::EVENT_NAME.",".self::EVENT_SHORTDESC." FROM `$table`;";
		return self::selectFn($db, $query,FALSE);
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
        
        
        function getCode() {
            return $this->code;
        }

        function getName() {
            return $this->name;
        }

        function getCat_id() {
            return $this->cat_id;
        }

        function getShort_desc() {
            return $this->short_desc;
        }

        function getLong_desc() {
            return $this->long_desc;
        }

        function getTags() {
            return $this->tags;
        }

        function getContacts() {
            return $this->contacts;
        }

        function getPrize() {
            return $this->prize;
        }

        function getPrtpnt() {
            return $this->prtpnt;
        }

        function getValidate() {
            return $this->validate;
        }

        function setCode($code) {
            $this->code = $code;
        }

        function setName($name) {
            $this->name = $name;
        }

        function setCat_id($cat_id) {
            $this->cat_id = $cat_id;
        }

        function setShort_desc($short_desc) {
            $this->short_desc = $short_desc;
        }

        function setLong_desc($long_desc) {
            $this->long_desc = $long_desc;
        }

        function setTags($tags) {
            $this->tags = $tags;
        }

        function setContacts($contacts) {
            $this->contacts = $contacts;
        }

        function setPrize($prize) {
            $this->prize = $prize;
        }

        function setPrtpnt($prtpnt) {
            $this->prtpnt = $prtpnt;
        }

        function setValidate($validate) {
            $this->validate = $validate;
        }


        function getLocation_id() {
            return $this->location_id;
        }

        function getTimings() {
            return $this->timings;
        }

        function setLocation_id($location_id) {
            $this->location_id = $location_id;
        }

        function setTimings($timings) {
            $this->timings = $timings;
        }



        
        
}

?>