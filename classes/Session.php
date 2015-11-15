<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Session
 *
 * @author anas
 */
class Session {
    
    const USER_ADMIN = 1;
    const USER_REGULAR = 2;
    const USER_MANAGER = 3;
    const USER_PROOFREADER = 4;
    
    private $loggedin = false;
    private $username;
    private $usertype;
    
    function __construct() {
        session_start();
        $this->checkLogin();
    }
    
    private function checkLogin(){
        if(isset($_SESSION['username'])&&isset($_SESSION['usertype'])){
            $this->username = $_SESSION['username'];
            $this->usertype = $_SESSION['usertype'];
            $this->loggedin = true;
        }else{
            unset($this->username);
            unset($this->usertype);
            $this->loggedin = false;
        }
    }
    
    function logIn($user,$user_type){
        if(!$user) return;
        $this->username = $_SESSION['username'] = $user;
        $this->usertype = $_SESSION['usertype'] = $user_type;
        $this->loggedin = true;
    }
    
    function logOut(){
        unset($_SESSION['username']);
        unset($_SESSION['usertype']);
        unset($this->username);
        unset($this->usertype);
        $this->loggedin = false;
    }
            
   function haveAccess($adminBit,$prBit,$emBit,$ruBit){
       switch($this->usertype){
           case self::USER_ADMIN: return true;break;
           case self::USER_MANAGER: return $emBit==1; break;
           case self::USER_PROOFREADER: return $prBit==1;break;
           case self::USER_REGULAR: return $ruBit==1;break;
           default:return false;break;
       }
   }

    

    function getLoggedin() {
        return $this->loggedin;
    }

    function getUsername() {
        return $this->username;
    }

    function getUsertype() {
        return $this->usertype;
    }

}



?>