<?php

class User{
    
        const USER_ADMIN = 1;
        const USER_REGULAR = 2;
        const USER_MANAGER = 3;
        const USER_PROOFREADER = 4;
        
        const USER_TABLE = "users";
	const USER_NAME = "username";
        const USER_PASSWORD = "password";
	const USER_EVENTCODE = "eventcode";
	const USER_VALIDATE = "validate";
	const USER_ROLL = "roll";
	const USER_PAGE = "page";
	const USER_MAC = "mac";
	const USER_SIGNUP = "signup";
	const USER_LASTLOGIN = "lastlogin";


	private $name;
	private $password;
	private $eventcode;
	private $validate;
        private $roll;
        private $page;
        private $mac;
        private $signup;
        private $lastlogin;
        private $type;

	function __construct($name,$password,$eventcode,$validate,
                $roll,$page,$mac,$signup,$lastlogin){
            
		$this->name = $name;
		$this->password = $password;
                $this->eventcode = $eventcode;
                $this->validate = $validate;
                $this->roll = $roll;
                $this->page = $page;
                $this->mac = $mac;
                $this->signup = $signup;
                $this->lastlogin = $lastlogin;
                $this->setTypefromCode();
		
	}
        
        private function setTypefromCode(){
                if($this->eventcode==NULL) return;
                
                if($this->eventcode=='-pr'){
                    $this->type = self::USER_PROOFREADER;
                }else if($this->eventcode=='admin'){
                    $this->type=self::USER_ADMIN;
                }else if($this->eventcode=='-nu'){
                    $this->type=  self::USER_REGULAR;
                }else{
                    $this->type = self::USER_MANAGER;
                }
        }
        
        public static function initWithArray($array){
            $instance = new self(NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
            $instance->setName($array[User::USER_NAME]);
            $instance->setPassword($array[User::USER_PASSWORD]);
            $instance->setEventcode($array[User::USER_EVENTCODE]);
            $instance->setValidate($array[User::USER_VALIDATE]);
            $instance->setRoll($array[User::USER_ROLL]);
            $instance->setPage($array[User::USER_PAGE]);
            $instance->setMac($array[User::USER_MAC]);
            $instance->setSignup($array[User::USER_SIGNUP]);
            $instance->setLastlogin($array[User::USER_LASTLOGIN]);
            $instance->setTypefromCode();
            return $instance;
        }
        
        

	static function insert($db,$name,$password,$eventcode,$validate,
                $roll,$page,$mac,$signup,$lastlogin){
               
                $table = self::USER_TABLE;
                $name = $db->escape($name);
                $password = $db->escape($password);
                $eventcode = $db->escape($eventcode);
                $validate = $db->escape($validate);
                $roll = $db->escape($roll);
                $page = $db->escape($page);
                $mac = $db->escape($mac);
                $signup = $db->escape($signup);
                $lastlogin = $db->escape($lastlogin);
                
                $sql = "INSERT INTO `$table` (`".self::USER_EVENTCODE."`, `".self::USER_NAME."`, "
                        . "`".self::USER_PASSWORD."`, `".self::USER_VALIDATE."`, "
                        . "`".self::USER_ROLL."`, `".self::USER_PAGE."`, `".self::USER_MAC."`, "
                        . "`".self::USER_SIGNUP."`, `".self::USER_LASTLOGIN."`) "
                        . "VALUES ('$eventcode', '$name', '$password', '$validate', '$roll',"
                        . " '$page', '$mac', '$signup', '$lastlogin')";
                
                return $db->query($sql);
		
	}

	static function updateColumn($db,$id,$column,$val){
            $table = self::USER_TABLE; 
            $a = self::USER_NAME;
            $column = $db->escape($column);
            $id = $db->escape($id);
            $val = $db->escape($val);
            return $db->query("UPDATE `$table` SET `$column` = '$val' WHERE `$a` = '$id'");     
        }

      
	static function delete($db,$id){
		
		$table = self::USER_TABLE;
		$id = $db->escape($id);
		$query = "DELETE FROM $table WHERE `".self::USER_NAME."` = '$id';";
		return $db->query($query);
	}
        
        static function select($db,$username,$isObject=true){
                $table = self::USER_TABLE;
                $username = $db->escape($username);
		$query = "SELECT * FROM `$table` WHERE ".self::USER_NAME." = '$username';";
                $objts = self::selectFn($db, $query, $isObject);
                if(sizeof($objts)==0)return NULL;
		else return $objts[0];

	}

	static function selectWithPass($db,$username,$pass,$isObject=true){
                $table = self::USER_TABLE;
                $username = $db->escape($username);
                $pass = $db->escape($pass);
		$query = "SELECT * FROM `$table` WHERE ".self::USER_NAME." = '$username' AND ".self::USER_PASSWORD." = '$pass';";
                $objts = self::selectFn($db, $query, $isObject);
                if(sizeof($objts)==0)return NULL;
		else return $objts[0];

	}
	static function selectAll($db,$isObject=true){
                $table = self::USER_TABLE;
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
;
        }
        
        private static function getJSONArray($result){

                     $object_array = array();
                     while($row = mysqli_fetch_assoc($result)){
                         $object_array[] = $row;
                     }
                    return $object_array;

        }
        
        
        

        function getName() {
            return $this->name;
        }

        function getPassword() {
            return $this->password;
        }

        function getEventcode() {
            return $this->eventcode;
        }

        function getValidate() {
            return $this->validate;
        }

        function getRoll() {
            return $this->roll;
        }

        function getPage() {
            return $this->page;
        }

        function getMac() {
            return $this->mac;
        }

        function getSignup() {
            return $this->signup;
        }

        function getLastlogin() {
            return $this->lastlogin;
        }

        function setName($name) {
            $this->name = $name;
        }

        function setPassword($password) {
            $this->password = $password;
        }

        function setEventcode($eventcode) {
            $this->eventcode = $eventcode;
        }

        function setValidate($validate) {
            $this->validate = $validate;
        }

        function setRoll($roll) {
            $this->roll = $roll;
        }

        function setPage($page) {
            $this->page = $page;
        }

        function setMac($mac) {
            $this->mac = $mac;
        }

        function setSignup($signup) {
            $this->signup = $signup;
        }

        function setLastlogin($lastlogin) {
            $this->lastlogin = $lastlogin;
        }



        function getType() {
            return $this->type;
        }

        function setType($type) {
            $this->type = $type;
        }


        
        
}

?>