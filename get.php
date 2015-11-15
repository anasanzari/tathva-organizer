<?php
require_once 'connection.php';

$session = new Session();
if (!$session->getLoggedin()) {
    header("Location: login.php");
}

$out = [];

if(isset($_GET['type'])){
    switch($_GET['type']){
        case 'reglist':$out = getRegList();break;
        case 'reglistold':$out = getRegListOld();break;
        case 'usermanipulation': $out = userManipultion();break;
        case 'eventmanipulation': $out = eventManipultion();break;
    }
}else{
    $out['status']= "fail";
    $out['error'] ="Type not set";
}

header("Content-Type: text/plain");
echo json_encode($out,JSON_PRETTY_PRINT);


function getRegList(){
    global $session;
    global $db;
   if($session->haveAccess(1, 1, 1, 1)){
        if(!isset($_GET['eventcode'])){
             $out['status'] = 'fail';
             $out['error'] = 'fill in more details :P';
        }else{
           $ecode = $_GET['eventcode'];
            $result = $db->query("SELECT e.team_id, e.tat_id, s.name as name, s.phone, s.email, c.name as clg FROM event_reg e INNER JOIN student_reg s ON e.tat_id=s.id INNER JOIN colleges c ON s.clg_id=c.id WHERE e.code='$ecode'");
            $regDetails = $db->getJSONArray($result);
            $out['status'] = 'success';
            $out['data'] = $regDetails;  
        }
       
   }else{
       $out['status'] = 'fail';
       $out['error'] = 'no access';
   }
   return $out;
}

function getRegListOld(){
    global $session;
    $db = new Database(DB_SERVER, DB_USER, DB_PASS, "nitcfest_tathva14");
   if($session->haveAccess(1, 1, 1, 1)){
        if(!isset($_GET['eventcode'])){
             $out['status'] = 'fail';
             $out['error'] = 'fill in more details :P';
        }else{
           $ecode = $_GET['eventcode'];
            $result = $db->query("SELECT e.team_id, e.tat_id, s.name as name, s.phone, s.email, c.name as clg FROM event_reg e INNER JOIN student_reg s ON e.tat_id=s.id INNER JOIN colleges c ON s.clg_id=c.id WHERE e.code='$ecode'");
            $regDetails = $db->getJSONArray($result);
            $out['status'] = 'success';
            $out['data'] = $regDetails;  
        }
       
   }else{
       $out['status'] = 'fail';
       $out['error'] = 'no access';
   }
   return $out;
}

function userManipultion(){
    global $session;
    global $db;
    
    if($session->haveAccess(1, 0, 0, 0)){
        if(!isset($_GET['username'])&&!isset($_GET['userop'])){
             $out['status'] = 'fail';
             $out['error'] = '<!---- Gold Fish ---->';
        }else{
            $username = $_GET['username'];
            $uop = $_GET['userop'];
            if($uop=='validate'){
                User::updateColumn($db, $username, User::USER_VALIDATE, 1);
                $out['data'] = "validate";
            }else if($uop=="invalidate"){
                User::updateColumn($db, $username, User::USER_VALIDATE, 0);
                $out['data'] = "invalidate";
            }else if($uop=="delete"){
                User::delete($db, $username);
                $out['data'] = "delete";
            }else{
                $out['data'] = "invalid operation!!";
            }     
            $out['status'] = 'success'; 
        }
       
   }else{
       $out['status'] = 'fail';
       $out['error'] = 'no access';
   }
   return $out;
    
    
}


function eventManipultion(){
    global $session;
    global $db;
    
    if($session->haveAccess(1, 0, 0, 0)){
        if(!isset($_GET['eventcode'])&&!isset($_GET['eventop'])){
             $out['status'] = 'fail';
             $out['error'] = '<!---- Gold Fish ---->';
        }else{
            $eventcode = $_GET['eventcode'];
            $eventop = $_GET['eventop'];
            if($eventop=='validate'){
                Event::updateColumn($db, $eventcode, Event::EVENT_VALIDATE, 1);
                $out['data'] = "validate";
            }else if($eventop=="invalidate"){
                Event::updateColumn($db, $eventcode, Event::EVENT_VALIDATE, 0);
                $out['data'] = "invalidate";
            }else if($eventop=="delete"){
                Event::delete($db, $eventcode);
                $out['data'] = "delete";
            }else{
                $out['data'] = "invalid operation!!";
            }     
            $out['status'] = 'success'; 
        }
       
   }else{
       $out['status'] = 'fail';
       $out['error'] = 'no access';
   }
   return $out;
    
    
}

?>

