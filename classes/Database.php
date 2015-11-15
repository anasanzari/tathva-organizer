<?php

 class Database{
     
     private $db;
     private $lastquery="";
     
     function __construct($server, $sqlid, $sqlpass, $dbase) {
         $this->connect($server, $sqlid, $sqlpass, $dbase);
     }
     public function connect($server, $sqlid, $sqlpass, $dbase){
         $this->db = new mysqli($server, $sqlid, $sqlpass, $dbase);
         if (mysqli_connect_errno()) die("Failed to connect to MySQL: " . mysqli_connect_error());	
     }
     
     public function query($sql){
         $this->lastquery = $sql;
         $result = $this->db->query($sql);
         if(!$result){
             die("Query failed.");
         }
         return $result;
     }
     
     public static function getJSONArray($result){

                     $object_array = array();
                     while($row = mysqli_fetch_assoc($result)){
                         $object_array[] = $row;
                     }
                    return $object_array;

        }
     
     public function autocommit($t){
         $this->db->autocommit($t);
     }
     
     public function rollback(){
         $this->db->rollback();
     }
     
     public function commit(){
         $this->db->commit();
     }
             
     
     public function escape($value){
         return mysql_escape_string($value);
     }
      
     public function close(){
         if(isset($this->db)){
             mysqli_close($this->db);
             unset($this->db);
         }
     }
     
 }

?>