<?php
 $target_dir = "images/";
 $target_file = $target_dir . basename($_FILES["photo"]["name"]);
 $uploadOk = 1 ;
 $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
 $check =getimagesize($_FILES["photo"]["tmp_name"]);
 if($check !== false){
     echo "File is an imag -" . $check["mime"] . ".";
     $uploadOk = 1 ;
     if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)){
         echo "The file". basename($_FILES["photo"]["name"]). "has been uploaded.";
     }else{
         echo "Sorry, There was an error uploading you file.";
     }
 }else{
     echo "File is not an image.";
     $uploadOk = 0 ;
 }
 ?>