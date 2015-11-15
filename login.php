<?php
require_once 'connection.php';
require_once("modules/mac.php");

$session = new Session();

if (isset($_POST["signin"])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $user = User::selectWithPass($db, $user, $pass);
    if($user&&$user->getValidate()==1){
        $code = $user->getEventcode();
        
        switch ($code){
            case 'admin':$session->logIn($user->getName(), Session::USER_ADMIN);break;
            case '-pr':$session->logIn($user->getName(), Session::USER_PROOFREADER);break;
            case '-nu':$session->logIn($user->getName(), Session::USER_REGULAR);break;
            default:$session->logIn($user->getName(), Session::USER_MANAGER);break;
        }
        if($session->getLoggedin()){
            header("Location:".$user->getPage());
        }
    
    }else{
        if($user && $user->getValidate()!=1) $msg = "Sorry. You are not yet validated. Contact Events Incharge.";
        else $msg = "Invalid usernam/password.";
    }
}

if (isset($_POST["signup"]) && isset($_POST["passphrase"]) && $_POST['passphrase'] == PHRASE ) {
    
    $uname = $_POST['uname'];
    $pass = $_POST['pass'];
    $roll = $_POST['roll'];
    $user = User::select($db, $uname);
    $date = date("Y-m-d H:i:s");
     
    if($user==NULL){
            if ($_POST["type"] != "mn") {
                $type = '-'.$_POST['type']; 
                if($_POST["type"]=="pr") $page = "proofreader.php"; 
                else $page="regular.php";

                if(User::insert($db, $uname, $pass, $type, 0, $roll, $page, $mac, $date, $date)){
                       $msg = "Signed Up Successfully.";
                }  
            }else if ($_POST["type"] == "mn") {
                $ecode = $_POST['ecode'];
                $ename = $_POST['ename'];
                //$ename = str_replace("'", "&#39;", $ename);
                $category = $_POST['category'];
                $db->autocommit(FALSE);
                if(User::insert($db, $uname, $pass, $ecode, 0, $roll, 'manager.php', $mac, $date, $date)){
                   if(Event::insert($db, $ecode, $ename, $category, "", "", "", "", "", "", "",0,"")){
                       $db->commit();
                        $msg = "Signed Up Successfully.";
                   }else{
                       $db->rollback();
                       $msg = "Event code alreadt exist.";
                   }
                }else{
                    $db->rollback();
                    $msg = "Unknown error occured.";
                }
                $db->autocommit(TRUE);
            }
    }else{
         $msg = "Username already exist.";
    }
    
}else{
    $passphrase = isset($_POST['passphrase'])?$_POST['passphrase']:"";
    if(isset($_POST['passphrase'])&&$passphrase != PHRASE) {
      $msg = "Signup Failed";
    }
}
    
     
require './includes/metadetails.php';
?>


    <body>
        
        <header>
            <img src="./img/logo.png" class="logo" style="position:absolute;top:0;left:0"/>
            <h1 class="center"><span class="tathva">tathva'15</span> Organizer</h1>    
        </header>
        
        <?php 
            if(isset($msg)&&$msg!=""){
              echo '<h2 class="center text-error">'.$msg."</h2>";
              $msg="";
            }
        ?>

        <div class="container-fluid">
            
            <div class="row">

                <div class="col-md-6">
                    <div id="sinwrap">
                        <form action="login.php" method="post" id="sinform">
                            <h3>Login</h3>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Username" name="username" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" placeholder="Password" name="password" required>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-default" name="signin" value="Sign In">
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-6">

                    <div id="supwrap">
                        <form action="#" method="post" id="supform" name="supform">
                            <h3>Sign Up</h3>
                            <div class="form-group">
                                <input type="text" class="form-control" name="uname" placeholder="Username" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="roll" placeholder="Roll Number (in caps)" >
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="pass" id="password" placeholder="Password" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="repass" placeholder="Retype password">
                            </div>
                            <div class="form-group">
                                <select id="acctype" class="form-control" name="type">
                                    <option value="nu">Regular User</option>
                                    <option value="pr">Proofreader</option>
                                    <option value="mn">Event Manager</option>
                                </select>
                            </div>


                            <div id="mn_opts">
                                <div class="form-group">   
                                    <select name="category" class="form-control" id="eventcat">
                                        <option value="">--Event Category--</option>
                                        <?php
                                        $cats = EventCategory::selectWithParCat($db, -1);
                                        foreach ($cats as $cat) {
                                            $subs = EventCategory::selectWithParCat($db, $cat->getId());
                                            if (sizeof($subs) == 0) {
                                                echo "<option value='{$cat->getId()}'>{$cat->getName()}</option>";
                                            } else {
                                                echo "<optgroup label='{$cat->getName()}'>";
                                                foreach ($subs as $sub) {
                                                    echo "<option value='{$sub->getId()}'>{$sub->getName()}</option>";
                                                }
                                                echo "</optgroup>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <input type="text" id="eventname" class="form-control" placeholder="Event Name" name="ename">
                                </div>
                                <div class="form-group">
                                    <input type="text" id="eventcode" class="form-control" placeholder="Event Code (3 letters)" name="ecode"><br/>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="text" name="passphrase" placeholder="Passphrase" id="passphrase">
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-default" name="signup" value="Sign Up">
                            </div>
                        </form>
                    </div>

                </div>

            </div>



        </div>

        <script>
            $.validator.addMethod("pattern", function(value, element, param) {
                    if (this.optional(element)) {
                            return true;
                    }
                    if (typeof param === "string") {
                            param = new RegExp("^(?:" + param + ")$");
                            
                    }
                    return param.test(value);
            }, "Invalid format.");

            $(document).ready(function() {
                var $mn_opts = $("#mn_opts");
                var $eventcat = $("#eventcat");
                var $eventname = $("#eventname");
                var $eventcode = $("#eventcode");
                var rule = { required:true};
                var codeRule = {required:true,pattern:/^[A-Z]{3}$/};
                
                $mn_opts.hide();
                $("#acctype").change(function() {
                    if($(this).val()=="mn"){
                        $mn_opts.slideDown();
                        $eventcat.rules( "add",rule);
                        $eventcode.rules("add",codeRule);
                        $eventname.rules("add",rule);
                    }else{
                        $mn_opts.slideUp();
                        $eventcat.rules("remove");
                        $eventcode.rules("remove");
                        $eventname.rules("remove");
                    }
                });
                $("#supform").validate({
                    rules:{
                        roll:{
                            required:true,
                            pattern:/^B13\d{4}[A-Z]{2}$/
                        },
                        pass:{
                            minlength:5
                        },
                        repass:{
                           equalTo: "#password"
                        }
                    }
                });
                
                $("#sinform").validate();
            });
        </script>

    </body>
</html>