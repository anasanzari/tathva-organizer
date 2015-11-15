<?php
   
$dir = "./uploads/";

if(isset($_GET['code'])&&isset($_GET['num'])){
    $name = $_GET['code'] ."_". $_GET['num'];
    $target = "./uploads/".$name;

    
    if(file_exists($target.".jpg")){
        //it jey peg
        $image = imagecreatefromjpeg($target.".jpg");
        header("Content-Type: image/jpeg");
        imagejpeg($image);
        
    }else if(file_exists($target.".png")){
        $image = imagecreatefrompng($target.".png");
        header("Content-Type: image/png");
        imagepng($image);
    }else if(file_exists($target.".gif")){
        $image = imagecreatefromgif($target.".gif");
        header("Content-Type: image/gif");
        imagegif($image);
    }else{
        $image = imagecreatefromjpeg($dir."default.jpg");
        header("Content-Type: image/jpeg");
        imagejpeg($image);
    }
    
}else{
        $image = imagecreatefromjpeg($dir."default.jpg");
        header("Content-Type: image/jpeg");
        imagejpeg($image);
}


?>

