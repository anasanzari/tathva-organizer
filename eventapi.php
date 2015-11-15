<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1">
        <title>Tathva '15</title>
        <link rel="stylesheet" href="./css/bootstrap.min.css" type="text/css" media="all" />
        <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        
        <style>
            .desc{
                background:#000;
                font-size: 1em;
                width:75%;
                margin: 0 auto;
            }
        </style>
        
    </head>


    <body style="background: #333;color:#fff">
    
    <div class="container-fluid">
	        
        <div id="details">
            
        </div>
            
    </div>
    
    <script>
        $details = $("#details");
        $.get("./api/getEvent.php?code=DEM",function(data){
           
            data = JSON.parse(data);
            $details.html("");
            $("<h1/>").text(data.name).appendTo($details);
            $("<h2/>").text(data.cat_name).appendTo($details);
            $("<p/>").html(data.shortdesc).appendTo($details);
            $("<h2/>").text("Long Description").appendTo($details);
            var descriptions = data.longdesc;
            $.each(descriptions, function(name, value) {
                $("<h3/>").html(name).appendTo($details);
                $("<div/>",{class: 'desc'}).html(value).appendTo($details);
            });
            $("<h2/>").text("Contacts").appendTo($details);
            var contacts = data.contacts;
            
            $.each(contacts, function(index,val) {
                $("<h2/>").text("contact").appendTo($details);
                $("<li>").text(val.name).appendTo($details);
                 $("<li>").text(val.phone).appendTo($details);
                  $("<li>").text(val.fb).appendTo($details);
                
            });
           
        });
    
    </script>
    
</body>
</html>