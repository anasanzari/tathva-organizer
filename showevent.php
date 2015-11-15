<?php
require_once 'connection.php';
$eventcode = $_GET['eventcode'];
$query="SELECT code, name, (SELECT name FROM event_cats WHERE event_cats.cat_id=events.cat_id) AS cat, shortdesc, longdesc, tags, contacts, prize, validate FROM events WHERE code = '$eventcode'";
$result=$db->query($query);
$array = $db->getJSONArray($result);

$event = Event::select($db, $eventcode);

if (!$event){
    die("Funny how curious you are!!...");
}

    $eventname=$event->getName();
    $shortdesc=$event->getShort_desc();
    $longdesc=$event->getLong_desc();
    $tags=$event->getTags();
    $contacts=$event->getContacts();
    $prize=$event->getPrize();
    $prtpnt=$event->getPrtpnt();
    $timings = $event->getTimings(); 
    
    $cat = EventCategory::select($db, $event->getCat_id());
    $category = $cat->getName();
      
    $locid = $event->getLocation_id();
    $loc = Location::select($db, $locid);
    $location = isset($loc)? $loc->getName(): "Not Set";  
    

    $m = explode("||@||",$prtpnt);
    $max = $min = "Not Set";
    if(sizeof($m)==2){
        $max = $m[1];
        $min = $m[0];
    }
    

$sections = explode("||sec||", $longdesc);
foreach ($sections as $section) {
    $det = explode("||ttl||", $section);
    if(sizeof($det)==1){
        $details['Introduction'] = $det[0];
    }else{
        $details[$det[0]] = $det[1];
    }
}
$con = [];
$contacts = explode("||0||", $contacts);
foreach ($contacts as $contact) {
    $con[] = explode("||@||", $contact);
}


require './includes/metadetails.php';
?>

<body>
    
    <div style="width:90%;margin:0 auto">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h3><?=$eventname?></h3>
                    <h4>Event Code: <?=$eventcode?></h4>
                    <h4>Event Category: <?=$category?></h4>
                    <p><strong>Short Description: </strong><?=$shortdesc?></p>
                    <p><strong>Tags: </strong><?=$tags?></p>
                    <p><strong>Prizes: </strong><?=$prize?></p>
                    <p><strong>Min Participants: </strong><?=$min?></p>
                     <p><strong>Max Participants:  </strong><?=$max?></p>
                </div>
                <div class="col-md-6">
                    <h3></h3>
                    
                    <p><strong>Contacts: </strong></p>
                    <ul>
                    <?php
                   
                    $index = 0;
                    foreach ($con as $contact) {
                        if($index>=3||  sizeof($con)<2) break;
                        $index++;
                        echo "<li><ol><li>".$contact[0]."</li>".
                                "<li>".$contact[1]."</li>".
                                "<li>".$contact[2]."</li></ol></li>";

                    }?>
                    </ul>
                    
                     <p><strong>Venue: </strong><?=$location?></p>
                     <p><strong>Timings: </strong><?=$timings?></p>
                     
                </div>
                
               
                
            </div>
            
            <div class="row">
                <div style="width:90%;border:1px solid #fff;padding: 5px;">
                 <ul class="nav nav-tabs" role="tablist">
                   <?php
                   $index = 0;
                   foreach ($details as $key => $value) {
                       if($key=="Introduction"){
                           echo '<li role="presentation" class="active">';
                       }else{
                           echo '<li role="presentation">';
                       }
                       echo '<a href="#tab_'.$index.'" data-toggle="tab">'.$key.'</a></li>';
                       $index++;
                   }
                    
                  ?>
                </ul>
                <div class="tab-content long-desc">
                    <?php
                    $index = 0;
                   foreach ($details as $key => $value) {
                       if($key=="Introduction"){
                           echo '<div role="tabpanel" class="tab-pane active" id="tab_'.$index.'">';
                       }else{
                           echo '<div role="tabpanel" class="tab-pane" id="tab_'.$index.'">';
                       }
                       echo $value."</div>";
                       $index++;
                   }?>
                  
                </div>
               </div>
        </div>
    </div>
        <h1></h1>
    </div>
    
</body>
</html>


