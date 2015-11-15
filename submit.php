<?php

require_once 'connection.php';


$session = new Session();
$user = User::select($db, $session->getUsername());

//print_r($_POST);

if (!$session->getLoggedin()||!$session->haveAccess(1, 1, 1,0)||($session->getUsertype()==Session::USER_MANAGER 
            && $user->getEventcode()!=$_GET['eventcode'])) {
    die("People of India posses a great deal of wisdom for changing what is not their.");
}
$out= [];

if(isset($_POST['eventcode'])) {
    
    $eventcode = $db->escape($_POST['eventcode']);
    $eventname=$db->escape(str_replace("'","&#39;",$_POST['ename']));
    $shortdesc=$db->escape(str_replace("'","&#39;",$_POST['shortdesc']));
    $tags=$db->escape($_POST['tags']);
    $contacts=$db->escape($_POST['contacts']);
    $prizes=$db->escape($_POST['prizes']);
    $longdesc=$db->escape($_POST['longdesc']); //single quotes - replaced with javascript .. (really???)
    $prtpnt=$db->escape($_POST['prtpnt']);
    $timings=$db->escape($_POST['timings']);
    $loc = $db->escape($_POST['venue']);
    
    $query="UPDATE events SET "
            .Event::EVENT_NAME. " ='$eventname',"
            .Event::EVENT_SHORTDESC. "='$shortdesc',"
            .Event::EVENT_LONGDESC."='$longdesc',"
            .Event::EVENT_TAGS. "='$tags',"
            .Event::EVENT_CONTACTS. "='$contacts',"
            .Event::EVENT_PRIZE."='$prizes',"
            .Event::EVENT_PRTPNT."='$prtpnt',"
            .Event::EVENT_TIMINGS."='$timings',"
            .Event::EVENT_LOCATIONID."='$loc' WHERE "
            .Event::EVENT_CODE. "='$eventcode'";
    $db->query($query);
    $status = "Success Fully Updated!!";
    
}else{
    $status = "Success Fully Failed :P ---> This shouldnt be happening!! Contact Incharge.";
}
require './includes/metadetails.php';

?>
<body>
    <h1><?=$status?></h1>
</body>
</html>