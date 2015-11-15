<?php

require '../config.php';
require '../classes/Database.php';
require '../classes/Event.php';
require '../classes/EventCategory.php';
require '../classes/Location.php';

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$out = [];

if (isset($_GET['code'])) {
    $code = $_GET['code'];
    if (isset($_GET['type']) && $_GET['type'] == 'short') {
        $e = Event::selectShort($db, $code, FALSE);
        if ($e != NULL)
            $out = $e;
    }else {
        $e = Event::select($db, $code, FALSE);
        //print_r($e);
        if ($e != NULL) {
            $cat = EventCategory::select($db, $e[Event::EVENT_CAT_ID]);
            $e["cat_name"] = $category = $cat->getName();

            $locid = $e[Event::EVENT_LOCATIONID];
            $loc = Location::select($db, $locid);
            $location = isset($loc) ? $loc->getName() : "notset";
            $loc_type = isset($loc) ? $loc->getType() : "notset";
            $loc_lon = isset($loc) ? $loc->getLongitude() : "notset";
            $loc_latt = isset($loc) ? $loc->getLattitude() : "notset";

            $e['location_name'] = $location;
            $e['location_type'] = $loc_type;
            $e['location_lattitude'] = $loc_latt;
            $e['location_longitude'] = $loc_lon;

            $longdesc = $e[Event::EVENT_LONGDESC];
            $sections = explode("||sec||", $longdesc);
            foreach ($sections as $section) {
                $det = explode("||ttl||", $section);
                if (sizeof($det) == 1) {
                    $details['Introduction'] = $det[0];
                } else {
                    $details[$det[0]] = $det[1];
                }
            }
            $e[Event::EVENT_LONGDESC] = $details;

            $con = [];
            $contacts = $e[Event::EVENT_CONTACTS];
            $contacts = explode("||0||", $contacts);
            foreach ($contacts as $contact) {
                if($contact=="") continue;
                $cons = explode("||@||", $contact);
                $a["name"] = $cons[0];
                $a["phone"] = $cons[1];
                $a["fb"] = $cons[2];
                $con[] = $a;
            }
            $e[Event::EVENT_CONTACTS] = $con;
            
            $prtpnt =$e[Event::EVENT_PRTPNT];
            $arr = explode("||@||",$prtpnt);
            $e["min_participants"] = ($arr[0]=="" ? "notset" : $arr[0]);
            $e["max_participants"] = ($arr[1]=="" ? "notset" : $arr[1]);
            
            $out = $e;
        } else {
            $out = [];
        }
    }
} else {
    $out = [];
}


header('Content-type: text/plain');

if (isset($_GET['pretty'])) {
    echo json_encode($out, JSON_PRETTY_PRINT);
} else {
    echo json_encode($out);
}
?>

