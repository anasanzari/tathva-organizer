<?php

$link = "http://tathva.org/organiser15/getImage.php";

function clean($val){
    $val = addslashes($val);
    return $val;
}



$upload_error = array(
    UPLOAD_ERR_OK => "No errors",
    UPLOAD_ERR_INI_SIZE => "File is too large. Limit(256 KB).",
    UPLOAD_ERR_FORM_SIZE => "File is too large. Limit(256 KB).",
    UPLOAD_ERR_PARTIAL => "Upload failed",
    UPLOAD_ERR_NO_FILE => "No file",
);
        
$max_file_size = 262144;
function attach_file($file,$code,$num){
    global $link;
    if(!$file || empty($file) || !is_array($file) ){
        $out['error'] = "No file Uploaded!!";
        $out['status'] = "fail";
        return $out;
    }
    if($file['error'] != 0){
        if($file['error']==UPLOAD_ERR_INI_SIZE || $file['error']==UPLOAD_ERR_INI_SIZE){
           $out['error'] = "Upload failed. File Size is too large. Limit( 256 KB)";
        }else{
            $out['error'] = "Upload failed. Unknown reason.";
        }
        $out['status'] = "fail";
        return $out;
    }else if($file['error']== UPLOAD_ERR_NO_FILE){
       
        $out['error'] = "No file Uploaded!!";
        $out['status'] = "fail";
        return $out;
    }
    
    $temp = $file['tmp_name'];
    $filename = basename($file['name']);
    $type = $file['type'];
    $size = $file['size'];
    
    if($size> 262144){ // size check
        $out['error'] = "Upload failed. File Size is too large. Limit( 256 KB)";
        $out['status'] = "fail";
        return $out;
    }
  
    $allowed = array("image/png","image/jpeg","image/gif");
    if(!in_array($type, $allowed)){
        echo "<p>Upload failed.Unrecognised file extension.</p>";
        $out['error'] = "Upload failed. Unrecognised file extension. Limit( 256 KB)";
        $out['status'] = "fail";
        return $out;
    }
    switch($type){
      case "image/png" : $extn = ".png";break;
      case "image/jpeg" : $extn = ".jpg";break; 
       case "image/gif" : $extn = ".gif";break;  
    }
    /*$dimens = getimagesize($temp);
    if($dimens[0] != $dimens[1]){
        echo "<p>Image Dimensions should match.Photo is not updated.</p>";
        return false;
    }*/
    $name = $code."_".$num;
    $target = $name.$extn;
    $upload_dir = "./uploads";
    if(move_uploaded_file($temp, $upload_dir."/".$target)){
        $out['error'] = "No errors!!";
        $out['status'] = "success";
        $out['link'] = $link."?code=".$code."&num=".$num;
        
        return $out;
       
    }
    
    $out['error'] = "Upload failed. Unknown Reason.";
    $out['status'] = "fail";
    return $out;
    
}

$out = [];

if(isset($_POST['code'])&&isset($_POST['picnum'])){
    $code = clean($_POST['code']);
    $num = clean($_POST['picnum']);
    $out = attach_file($_FILES['file_upload'],$code,$num);
}else{
    $out['error'] = "Failed.";
    $out['status'] = "fail";
}

echo json_encode($out);

?>

