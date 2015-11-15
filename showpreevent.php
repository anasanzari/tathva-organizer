<?php 

require_once 'connection.php';
if(!isset($_GET['eventcode'])){
    die("add eventcode");
}

$db = new Database(DB_SERVER, DB_USER, DB_PASS, "nitcfest_tathva14");

require './includes/metadetails.php';
?>

<body>
    
    <div class="container-fluid">
	<?php
        $eventcode = $_GET['eventcode'];
        $query="SELECT code, name, (SELECT name FROM event_cats WHERE event_cats.cat_id=events.cat_id) AS cat, shortdesc, longdesc, tags, contacts, prize, validate FROM events WHERE code = '$eventcode'";
        $result=$db->query($query);
        $row=$result->fetch_assoc();
        if (!$row)
            echo "Sorry events table is empty.";
        else {
        ?>
        
	<table class="table table-condensed">
		<colgroup>
			<col style="width: 102px">
			<col style="width: 902px">
		</colgroup>
  		
  		
    <?php
    do {
	$e = $row['code'];
	$x = "exec.php?e=$e";
	$v = "<a href='$x&a=";
	$v .= ($row['validate'] == 0) ? "val'>Validate" : "inv'>Invalidate";
	$v .= "</a>";
	echo "
		<tr><th>CODE</th><th>$e</th>
		</tr>
		<tr><th>NAME</th>
			<th>$row[name]</th>
		</tr>
		<tr><th>CATEGORY</th>
			<th>$row[cat]</th>
		<tr>
		<tr><th>SHORT DESCRIPTION</th>
			<th>$row[shortdesc]</th></tr>
		<tr><th>LONG DESCRIPTION</th>
			<th>
			<div class='overflow'>".str_replace(array('||sec||','||ttl||'),array('<h4>','</h4>'),$row['longdesc'])."</div>
			</th>
		</tr> 
		<tr><th>TAGS</th>
			<th>$row[tags]</th>
		</tr>
		<tr><th>CONTACT</th>
			<th>".str_replace(array("||0||","||@||"),array("<br/>"," "),$row['contacts'])."</th>
		</tr>
		<tr><th>PRIZE</th>
			<th>".str_replace("||@||","<br/>",$row['prize'])."</th>
		</tr> 
		
		</tr>";
    } while($row=$result->fetch_array());
    ?>
    </tr>
	</table>
    <?php
}
?>
        
        </div>
</body>
</html>