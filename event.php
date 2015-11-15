<?php
require_once 'connection.php';
$session = new Session();
if (!$session->getLoggedin()) {
    header("Location: login.php");
}

$user = User::select($db, $session->getUsername());

if (!isset($_GET['eventcode']) &&
        $session->getUsertype() == Session::USER_REGULAR ||
        ($session->getUsertype() == Session::USER_MANAGER && $user->getEventcode() != $_GET['eventcode'])) {
    die("error!! No Access!!");
}

$eventcode = $_GET['eventcode'];
$event = Event::select($db, $eventcode);
$eventname = $event->getName();
$shortdesc = $event->getShort_desc();
$longdesc = $event->getLong_desc();
$tags = $event->getTags();
$contacts = $event->getContacts();
$prize = $event->getPrize();
$prtpnt = $event->getPrtpnt();
$timings = $event->getTimings();
$venue = $event->getLocation_id();
$venue = Location::select($db, $venue);


require './includes/metadetails.php';
?>
<body>
    <script type="text/javascript" src="js/markitup/jquery.markitup.js"></script>
    <script type="text/javascript" src="js/markitup/settings.js"></script>

    <?php require './includes/header.php'; ?>

    <div style="width:90%;margin:0 auto">
        <div class="container-fluid">
            <form method="post" action="submit.php" id="event_form" name="event_form">
                <div class="row">            
                    <div class="col-md-6">
                        <h4>Event Code: <?php echo $eventcode; ?></h4>
                        <input type="hidden"  class="form-control" name="eventcode" value="<?= $eventcode ?>" >
                        <div class="form-group">
                            <label>Event Name:</label>
                            <input type="text"  class="form-control" name="ename" value="<?php echo $eventname; ?>" >
                        </div>
                        <div class="form-group">
                            <label>Short Description</label>
                            <textarea class="form-control" name="shortdesc"><?php echo $shortdesc; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Tags</label>
                            <textarea class="form-control" name="tags"><?php echo $tags; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Prizes:</label>
                            <textarea id="pr" class="form-control" style="width:50%"></textarea>
                            <input type="hidden" name="prizes" id="pr_disp" value="<?php echo $prize; ?>" />
                        </div>
                        <div class="form-group">
                            <label>No of participants</label>
                            <div class="input-group">
                                <div class="input-group-addon">Min</div>
                                <input id="par_min" class="form-control" type="text" style='width:50%'/>
                            </div>
                            <div class="input-group">
                                <div class="input-group-addon">Max</div>
                                <input id="par_max" type="text" class="form-control" style='width:50%' />
                            </div>
                            <input type="hidden" name="prtpnt" id="prtpnt" value="<?php echo $prtpnt; ?>" />
                        </div>
                        <div class="form-group">
                            <label>Venue</label>
                            <select id="venue" name="venue" class="form-control">
                                <option value="">--Event Location--</option>
                                <?php
                                $locations = Location::selectAll($db);
                                foreach ($locations as $loc) {
                                    if ($venue != NULL && $venue->getId() == $loc->getId()) {
                                        echo "<option value='{$loc->getId()}' selected>{$loc->getName()}</option>";
                                    } else {
                                        echo "<option value='{$loc->getId()}'>{$loc->getName()}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Timings:</label>
                            <textarea id="timings" name="timings" class="form-control" style="width:50%"><?= $timings ?></textarea>

                        </div>
                    </div>

                    <div class="col-md-6">
                        <h4>Contacts</h4>
                        <!--div class="form-group">
                            <label>Event email-id</label>
                            <div class="input-group">
                                <input type="text" class="em form-control disabled" id="em3" placeholder="eMail" />
                                <div class="input-group-addon">@tathva.org</div>
                            </div>
                        </div-->
                        <div class="con form-group">
                            <label>Manager 1 <small>(preferred contact for queries)</small></label>
                            <input type="text" class="na form-control" id="na1" placeholder="Name" />
                            <div class="input-group">
                                <div class="input-group-addon">+91</div>
                                <input type="text" class="co form-control" id="co1" placeholder="Contact Number" />
                            </div>
                            <input type="text" class="fb form-control" id="fb1" placeholder="Facebook Profile link" />
                        </div>
                        <div class="con form-group">
                            <label>Manager 2</label>
                            <input type="text" class="na form-control" id="na2" placeholder="Name" />
                            <div class="input-group">
                                <div class="input-group-addon">+91</div>
                                <input type="text" class="co form-control" id="co2" placeholder="Contact Number" />
                            </div>
                            <input type="text" class="fb form-control" id="fb2" placeholder="Facebook Profile link" />
                        </div>
                        <div class="con form-group">
                            <label>Manager 3</label>
                            <input type="text" class="na form-control" id="na3" placeholder="Name" />
                            <div class="input-group">
                                <div class="input-group-addon">+91</div>
                                <input type="text" class="co form-control" id="co3" placeholder="Contact Number" />
                            </div>
                            <input type="text" class="fb form-control" id="fb3" placeholder="Facebook Profile link" />
                        </div>
                        <input type="hidden" name="contacts" id="con_disp" value="<?php echo $contacts; ?>" />

                    </div>    
                </div>


                <div class='row'>
                    <h2>Long Description</h2>

                    <a class="btn btn-default btn-lg" href="#editor_help" id="e_help">Help for editor</a>
                    <a class="btn btn-default btn-lg" href="#upload_help" id="u_help">Uploading Images</a>




                    <input type="hidden" id="desc" name="longdesc" value="<?php echo str_replace('"', '&quot;', $longdesc); ?>" />

                    <h3>Introduction</h2>
                        <textarea id="intro" name="intro" cols="80" rows="20"></textarea>
                        <a class='btn btn-default' href="javascript:void(0)" id="new_sec">+section</a>

                </div>

                <div class='row' style='margin-top: 25px;margin-bottom: 25px;'>
                    <div class='col-md-6'>
                        <input class="btn btn-default btn-lg" name="update" type="submit" value="Save All Information" />
                    </div>
                </div>


            </form>
        </div>
    </div>


    <div id="editor_help" class="fixed-div">
        <div class="container-fluid">
            <a href="#" id="editor_help_close" class="btn btn-default" style="margin-top: 5px"><span class="glyphicon glyphicon-remove"></span> Close</a>   
            <h1>Using the editor</h1>
            <div class="row">

                <div class="col-md-3">
                    <ul class="help_list">
                        <li><span class="btn btn-default glyphicon glyphicon-button1"></span><span class="detail">Heading One : Ctrl + 1</span></li>
                        <li><span class="btn btn-default glyphicon glyphicon-button2"></span><span class="detail">Heading Two : Ctrl + 2</span></li>
                        <li><span class="btn btn-default glyphicon glyphicon-button3"></span><span class="detail">Heading Three : Ctrl + 3</span></li>
                        <li><span class="btn btn-default glyphicon glyphicon-button4"></span><span class="detail">Heading Four : Ctrl + 4</span></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <ul class="help_list">
                        <li><span class="btn btn-default glyphicon glyphicon-button5"></span><span class="detail">Paragraph : Ctrl + P</span></li>
                        <li><span class="btn btn-default glyphicon glyphicon-button6"></span><span class="detail">Bold Text : Ctrl + B</span></li>
                        <li><span class="btn btn-default glyphicon glyphicon-button7"></span><span class="detail">Italics : Ctrl + I</span></li>
                        <li><span class="btn btn-default glyphicon glyphicon-button8"></span><span class="detail">Superscript : Ctrl + Q</span></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <ul class="help_list">
                        <li><span class="btn btn-default glyphicon glyphicon-button10"></span><span class="detail">Bulleted list</span></li>
                        <li><span class="btn btn-default glyphicon glyphicon-button11"></span><span class="detail">Numbered list</span></li>
                        <li><span class="btn btn-default glyphicon glyphicon-button12"></span><span class="detail">Add Image : Ctrl + E</span></li>
                        <li><span class="btn btn-default glyphicon glyphicon-button13"></span><span class="detail">Add Link : Ctrl + L</span></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <ul class="help_list">
                        <li><span class="btn btn-default glyphicon glyphicon-button14"></span><span class="detail">Clean</span></li>
                        <li><span class="btn btn-default glyphicon glyphicon-button15"></span><span class="detail">Preview</span></li>
                        <li><span class="btn btn-default glyphicon glyphicon-button9"></span><span class="detail">Subscript : Ctrl + W</span></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <ul class="help_notice">
                        <li>To convert a simple line-seperated list to an ordered/unodered list, select them and press one of the bulleting or numbering buttons shown above...</li>
                        <li>Pressing Ctrl+Enter : Creates a new list-item when inside lists (bounded by 'ul' for bulleted (unordered) and 'ol' for numbered (ordered)).</li>
                    </ul>
                </div>
            </div>

            <textarea id="demo" name="intro" cols="80" rows="20"><h1>Details</h1>
<h2>Sub heading</h2>
<ul>
    <li>This is a list item</li>
    <li>This is a list item</li>
    <li>This is a list item</li>
    <li>This is a list item</li>
</ul>
<h2>Numbered List</h2>
<ol>
    <li>Casino Royale</li>
    <li>Die another day</li>
</ol>
<p>Make sure you use paragraph, p tags, for adding details.
<br/>Line break
<br/><strong>Bold text</strong><em>italics</em>
<br/>
adding superscript x<sup>2</sup>
adding subscript x<sub>2</sub>
</p>
<img src="http://localhost/Organiser15/img/sample.jpg" alt="sample" />

<p> This is another paragraph, showing the importance of long details.
Tathva is not just tech fest, it is an understanding, an awareness.
Lots lots of people, do come to tathva. Proud to be part of tathva.</p>


            </textarea> 

        </div>
    </div>

    <div id="upload_help" class="fixed-div">
        <div class="container-fluid">
            <a href="#" id="upload_help_close" class="btn btn-default" style="margin-top: 5px"><span class="glyphicon glyphicon-remove"></span> Close</a>   
            <h1> Uploading Images</h1>
            <p>Inorder to maintain common pattern, images are named in the format 'EVENTCODE_NUMBER.extension'.
                Make sure you number the images correctly.
                <br/>
                The uploaded images can be inserted to details by giving the link in the following format.<br/>
                <strong>http://tathva.org/organiser15/getImage.php?code=CODE&num=NUM</strong>
            </p>
            <div class="row" style="padding-bottom: 10px">   
                <form id="theform" enctype="multipart/form-data" method="POST">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Image Name</label>
                            <div class="input-group">
                                <div class="input-group-addon"><?= $eventcode ?>_</div>
                                <input type="hidden" name="code" value="<?= $eventcode ?>" />
                                <input type="number" class="form-control" name="picnum" placeholder="Picture Number" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Upload Photo</label>
                            <input id="file" type="file" name="file_upload" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="submit" class="btn btn-default">Upload</button>
                            <img id="loader" src="./img/loader.gif" style="margin-left:5px;height: 30px;width:30px" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id='uploaded-image'></div>
                    </div>   


                </form>

            </div>



        </div>

        <script type="text/javascript">
            function remove_sec() {
                $(this).remove();
            }
            function get_par_sec(e) {
                return $(e).closest(".desc-sec");
            }

            function new_desc_sec(title, content) {
                var link = $("#new_sec");
                var new_section = $("<div/>", {
                    class: "desc-sec"
                });
                var desc_head = $("<div/>", {
                    class: "desc-head"
                });
                var head_menu = $("<div/>", {
                    class: "btn-group head-menu"
                });
                //$(..).attr({name: 'xyz'})
                $("<span/>", {
                    html: "",
                    class: "desc-but btn btn-default glyphicon glyphicon-remove",
                    title: "remove"
                }).click(function () {
                    get_par_sec(this).hide(400, remove_sec);
                }).appendTo(head_menu);
                $("<span/>", {
                    html: "",
                    class: "desc-but btn btn-default glyphicon glyphicon-arrow-down",
                    title: "move down"
                }).appendTo(head_menu).click(function () {
                    var par_sec = get_par_sec(this);
                    var next = par_sec.next(".desc-sec");
                    par_sec.insertAfter(next);
                    var c = par_sec.find(".markItUpPreviewFrame");
                    c.remove();
                    if (next.length != 0) {
                        $('body,html').animate({scrollTop: '+=600px'}, 400);
                    }

                });
                $('<span />', {
                    html: "",
                    class: "desc-but btn btn-default glyphicon glyphicon-arrow-up",
                    title: "move up"
                }).appendTo(head_menu).click(function () {
                    var par_sec = get_par_sec(this);
                    var prev = par_sec.prev('.desc-sec');
                    par_sec.insertBefore(prev);
                    var c = par_sec.find(".markItUpPreviewFrame");
                    c.remove();
                    if (prev.length != 0) {
                        $('body,html').animate({scrollTop: '-=600px'}, 400);
                    }

                });

                head_menu.appendTo(desc_head);
                var sec_ttl = $('<div class="form-group"><label>Section Title</label><input type="text" class="form-control" style="width:50%" /></div>').appendTo(desc_head);

                var desc_src = document.createElement("textarea");
                new_section.hide();
                new_section.insertBefore(link);
                desc_head.appendTo(new_section);

                $(desc_src).appendTo(new_section);
                $(desc_src).markItUp(mySettings);
                $(desc_src).attr("cols", 80);
                $(desc_src).attr("rows", 20);
                $('body,html').animate({
                    scrollTop: '+=' + new_section.height()
                }, 400);
                new_section.show(400);
                if (title) {

                    sec_ttl.children("input").val(title);

                    if (content) {
                        desc_src.value = content;
                    }
                } else
                    sec_ttl.focus();
            }
            $(document).ready(function () {
                // new_kaja_input($("#intro"));
                /* fixed div help*/
                var $editor_help = $("#editor_help");
                $editor_help.hide();
                $("#editor_help_close").click(function () {
                    $editor_help.slideToggle();
                });
                $("#e_help").click(function () {
                    $editor_help.slideToggle();
                });
                /* fixed div help-----end------*/
                /* fixed div uploader*/

                var $upload_help = $("#upload_help");
                $upload_help.hide();
                $("#upload_help_close").click(function () {
                    $upload_help.slideToggle();
                });
                $("#u_help").click(function () {
                    $upload_help.slideToggle();
                });

                var $loader = $("#loader");
                $loader.hide();
                $("#theform").submit(function (e) {
                    e.preventDefault();
                    var dat = new FormData($(this)[0]);
                    $loader.show();
                    $.ajax({
                        url: "./ImageUpload.php",
                        type: "POST",
                        data: dat,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            $loader.hide();
                            var obj = JSON.parse(data);
                            if (obj.status == "success") {
                                $("#uploaded-image").html("<p>Success fully uploaded</p>")
                                        .append('<img src="' + obj.link + '"/>')
                                        .append('<p>Add Using the Link:<br/><b>' + obj.link + '</b></p>')
                                        .delay(500).fadeIn(500);

                            } else {
                                $("#uploaded-image").html(obj.error).delay(500).fadeIn(500);
                            }
                            /* if(data.substring(3,24)=="Uploaded Successfully"){
                             // alert(data.substring(3,24));
                             $loader.hide();
                             $.post("./get_url.php",{id:},function(data){
                             $("#prof_image").attr("src",data+"?"+new Date().getTime());
                             });
                     
                             }*/
                        },
                        error: function (error) {

                        }
                    });

                });



                /* fixed div uploader end **/

                $('#intro').markItUp(mySettings);
                $('#demo').markItUp(mySettings);

                $("#new_sec").click(function () {
                    new_desc_sec();
                });

                $("#event_form").submit(function () {
                    $("#con_disp").get(0).value = null;
                    $("#pr_disp").get(0).value = null;

                    $(".con").each(function (index) {

                        if ($(this).find(".na").val()) {
                            var n1 = $(this).find(".na").val();
                            var c1 = $(this).find(".co").val();
                            var f1 = $(this).find(".fb").val();
                            c1 = (c1 ? c1 : "notset");
                            f1 = (f1 ? f1 : "notset");
                            $("#con_disp").get(0).value += n1 + "||@||" + c1 + "||@||" + f1 + "||0||";
                        }

                    });

                    $("#pr_disp").get(0).value = $(this).find("#pr").val();

                    $("#prtpnt").get(0).value = $(this).find("#par_min").val() + "||@||" + $(this).find("#par_max").val();

                    var desc_hid = $("#desc").get(0);
                    desc_hid.value = $("#intro").val();
                    $(".desc-sec").each(function (index) {
                        $("#desc").get(0).value += "||sec||" + $(this).find("input").val() + "||ttl||" + $(this).find("textarea").val();
                    });
                    desc_hid.value = desc_hid.value.replace(/'/g, "&#39;").replace(/\u2014/g, "&#8211;");

                    //console.log($(this).serialize());
                    return true;
                });
                //Filling descriptions
                var descs = $("#desc").get(0).value.split("||sec||");
                if (descs.length > 0) {
                    $("#intro").val(descs[0]);
                    //update_preview($("#intro").val(descs[0]).get(0));
                    if (descs.length > 1) {
                        var sec_data, i;
                        for (i = 1; i < descs.length; i++) {
                            sec_data = descs[i].split("||ttl||");
                            new_desc_sec(sec_data[0], sec_data[1]);
                        }
                    }
                    $("ul li.preview a").mouseup();
                }
                // Filling prizes and contacts
                var prv = $("#pr_disp").get(0).value;

                $("#pr").get(0).value = prv;

                var conv = $("#con_disp").get(0).value;
                var cons = conv.split("||0||");
                if (conv && cons.length > 0)
                {
                    var cnt, j, k;
                    for (j = 0; j < 3; j++)
                    {
                        cnt = cons[j].split("||@||");
                        k = j + 1;
                        $("#na" + k).get(0).value = cnt[0];
                        $("#co" + k).get(0).value = cnt[1];
                        $("#fb" + k).get(0).value = cnt[2];
                    }
                   // $(".em").get(0).value = cnt[3];
                }

                var prtpnt = $("#prtpnt").get(0).value;
                pnt = prtpnt.split("||@||");
                
                $("#par_min").get(0).value = pnt[0];
                $("#par_max").get(0).value = pnt[1];
               /* if (prtpnt != 1)
                {
                    pnt = prtpnt.split("||@||");
                    $("#par_min").get(0).value = pnt[0];
                    $("#par_max").get(0).value = pnt[1];
                }
                else if (prtpnt == 1)
                {
                    $("#par_min").get(0).value = 1;
                    $("#par_max").get(0).value = 1;
                }*/
            });
            //-->
        </script>

</body>
</html>
