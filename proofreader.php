<?php
require_once 'connection.php';

$session = new Session();
if (!$session->getLoggedin()||$session->getUsertype()!=Session::USER_PROOFREADER) {
    header("Location: login.php");
}

$olddb = new Database(DB_SERVER, DB_USER, DB_PASS, "nitcfest_tathva14");
$oldevents = Event::selectAllShort($olddb); 
$oldoptions = "";
foreach ($oldevents as $event) {
  
    $oldoptions .= "<option value='{$event['code']}'>{$event['name']}</option>";
}

$events = Event::selectAllShort($db);
$options = "";
foreach ($events as $event) {
  
    $options .= "<option value='{$event['code']}'>{$event['name']}</option>";
}





require './includes/metadetails.php';
?>

<body>
<?php require './includes/header.php'; ?>

    <div class="container-fluid">

        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#reglist" aria-controls="reglist" role="tab" data-toggle="tab">T14 Reglist</a></li>
            <li role="presentation"><a href="#reglist15" aria-controls="reglist15" role="tab" data-toggle="tab">T15 Reglist</a></li>
            <li role="presentation"><a href="#events" aria-controls="events" role="tab" data-toggle="tab">Event List</a></li>
            <li role="presentation"><a href="#pre-events" aria-controls="eventm" role="tab" data-toggle="tab">T14 Events</a></li>
           
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="reglist">
                <h2>TATHVA '14 REGISTERATION LIST</h2>
                <form action="get.php" id="regform" method="post">
                    <select name="eventcode" id="event-select">
                        <option value="">--events--</option>
                        <?= $oldoptions ?>
                    </select>
                    <input type="hidden" name="type" value="reglistold" />
                    <input name="ersubmit" type="submit" value="Get Event Reg List">
                </form>
                <div>
                    <h1 id="noregdetails">No Registerations found for this event</h1>
                    <table id="regDetails" class="table table-condensed">
                        <thead>
                            <th>TEAM ID</th>
                            <th>TATHVA ID</th>
                            <th>NAME</th>
                            <th>CONTACT</th>
                            <th>EMAIL</th>
                            <th>COLLEGE</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
               
            </div>
            
            <div role="tabpanel" class="tab-pane" id="reglist15">
                <h2>TATHVA '15 REGISTERATION LIST</h2>
                <form action="get.php" id="regform15" method="post">
                    <select name="eventcode" id="event-select15">
                        <option value="">--events--</option>
                        <?= $options ?>
                    </select>
                    <input type="hidden" name="type" value="reglist" />
                    <input name="ersubmit" type="submit" value="Get Event Reg List">
                </form>
                <div>
                    <h1 id="noregdetails15">No Registerations found for this event</h1>
                    <table id="regDetails15" class="table table-condensed">
                        <thead>
                            <th>TEAM ID</th>
                            <th>TATHVA ID</th>
                            <th>NAME</th>
                            <th>CONTACT</th>
                            <th>EMAIL</th>
                            <th>COLLEGE</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
               
            </div>
            
            <div role="tabpanel" class="tab-pane" id="events">
                <div id="events">
                    <h1>Events</h1>
                    <table class="table table-condensed">
                        <thead><tr>
                                <th>Code</th>
                                <th>Event Name</th>
                                <th>Category</th>
                                <th>Edit Event</th>
                                <th>Show Details</th>
                            </tr></thead>
                        <tbody>
                        <?php
                        
                        $query = "SELECT code, name, (SELECT name FROM event_cats WHERE event_cats.cat_id=events.cat_id) AS cat, validate FROM events";
                        $result = $db->query($query);
                        $eventDetails = $db->getJSONArray($result);
                        $eventList = "";
                        foreach ($eventDetails as $row) {
                            
                            $val = ($row['validate'] == 0) ? "validate" : "invalidate";
                            $det = "<tr><td>".$row['code']."</td>".
                                    "<td>".$row['name']."</td>".
                                    "<td>".$row['cat']."</td>".
                                    '<td><a class="btn btn-default btn-xs" href="event.php?eventcode='.$row['code'].'" target="_blank">Edit Event</a></td>'
                                     . '<td><a class="btn btn-default btn-xs" href="showevent.php?eventcode='.$row['code'].'" target="_blank">show details</a></td>'
                                    . '</tr>';
                            echo $det;
                        }
                        ?>
                        </tbody>
                    </table>

                </div>

            </div>
            <div role="tabpanel" class="tab-pane" id="pre-events">
                <?php
                $db = new Database(DB_SERVER, DB_USER, DB_PASS, "nitcfest_tathva14");
                $query = "SELECT * FROM events";
                $result = $db->query($query);
                $events = $db->getJSONArray($result);
                foreach ($events as $event) {
                    echo '<a href="showpreevent.php?eventcode='.$event['code'].'" target="_blank" class="btn btn-default stacked"'.
                            '>'.$event['name'].'</a>';
                }
                
                ?>
            </div>

        </div>
    </div>


    <div class="progress-dialog hide">
        <img src="img/loader.gif" />
        <p>Processing...</p>
    </div>
    <div class="fade-div hide" id="fade-div"></div>
    
    <script>
        
        var $progressDialog = $(".progress-dialog");
        var $fadeDiv = $("#fade-div");
        
        function showProgress(){
            $progressDialog.removeClass("hide");
            $fadeDiv.removeClass("hide");
        }
        function hideProgress(){
            $progressDialog.addClass("hide");
            $fadeDiv.addClass("hide");
            
        }
        
        var $noregtext = $("#noregdetails");
        var $regDetails = $("#regDetails");
        $noregtext.hide();
        $regDetails.hide();
        
        $("#regform").submit(function(){
            if($("#event-select").val()=="") return false;
            showProgress();
            var data = $("#regform").serialize();
            $.getJSON("./get.php",data,function(data){
                hideProgress();
                if(data.status=='success'){
                    if(data.data.length==0){
                        $noregtext.slideDown(500);
                        $regDetails.hide();
                    }else{
                        $noregtext.hide();
                        var arr = data.data;
                        var det = "";
                        for(var i=0;i<arr.length;i++){
                            det += '<tr><td>'+arr[i].team_id+"</td>"+
                                   '<td>'+arr[i].tat_id+"</td>"+
                                   '<td>'+arr[i].name+"</td>"+
                                   '<td>'+arr[i].phone+"</td>"+
                                   '<td>'+arr[i].email+"</td>"+
                                   '<td>'+arr[i].clg+"</td></tr>";
                        }
                        
                        $regDetails.find("tbody").html(det);
                        $regDetails.hide().slideDown(500);
                    }
                }else{
                    alert("this shouldnt be happening!! some kind of error occured");
                }
            });
            return false;
        });
        
        
        var $noregtext15 = $("#noregdetails15");
        var $regDetails15 = $("#regDetails15");
        $noregtext15.hide();
        $regDetails15.hide();
        
        $("#regform15").submit(function(){
            if($("#event-select15").val()=="") return false;
            showProgress();
            var data = $("#regform15").serialize();
            $.getJSON("./get.php",data,function(data){
                hideProgress();
                if(data.status=='success'){
                    if(data.data.length==0){
                        $noregtext15.slideDown(500);
                        $regDetails15.hide();
                    }else{
                        $noregtext15.hide();
                        var arr = data.data;
                        var det = "";
                        for(var i=0;i<arr.length;i++){
                            det += '<tr><td>'+arr[i].team_id+"</td>"+
                                   '<td>'+arr[i].tat_id+"</td>"+
                                   '<td>'+arr[i].name+"</td>"+
                                   '<td>'+arr[i].phone+"</td>"+
                                   '<td>'+arr[i].email+"</td>"+
                                   '<td>'+arr[i].clg+"</td></tr>";
                        }
                        
                        $regDetails15.find("tbody").html(det);
                        $regDetails15.hide().slideDown(500);
                    }
                }else{
                    alert("this shouldnt be happening!! some kind of error occured");
                }
            });
            return false;
        });
        
        
        
       
        
        /*var $regDetails = $("#regDetails");
        var $regSelect = $("#event-select");
        $regSelect.change(function(val){
            var val = $(this).val();
            $.get
        });*/
        
    </script>
    
</body>
</html>