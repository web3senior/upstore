<?php

class Upload
{

  function storeImageFile($fileUploadTagName, $timePlus)
  {
    $message = "";
    /* if (isset($_POST["sbm"]))
      { */

    $name = $_FILES["$fileUploadTagName"]["name"]; // get the name of the file
    $type = $_FILES["$fileUploadTagName"]["type"]; // get the type of the file
    $size = $_FILES["$fileUploadTagName"]["size"];  // get the size of the file

        $allowed = ['.jpg', '.gif', '.bmp', '.png', '.ico', '.mp3', '.wave', '.webp', '.svg', '.jfif','.avif'];
    $max_filesize = 55524288;
    $upload_path = 'upload/images/';

    $ext = substr($name, strpos($name, '.'), strlen($name) - 1);

    $fileType = in_array($ext, $allowed);


    if (!$fileType) :
      $message = '<br />The file you attempted to upload is not allowed.';
    endif;


    if ($size > $max_filesize) :
      $message = 'The file you attempted to upload is too large.';
    endif;

    $upload = is_writable($upload_path);

    if (!$upload) :
      $message = 'You cannot upload to the specified directory, please CHMOD it to 777.';
    endif;


    $filename = (time() + $timePlus) . $ext;

    if ($fileType && $size < $max_filesize && $upload) {

      if (move_uploaded_file($_FILES["$fileUploadTagName"]["tmp_name"], $upload_path . $filename)) {
        return $filename;
        //echo time() . ' Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>';
        //$current_url = (empty($_SERVER['HTTPS']) ? "http://" : "https://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        //header ('Location: ' . $current_url);
        //exit ();
      }
    }
    /* } else
      {
      $message = 'no click to submit button';
      } */
    echo $message;
  }

  function documentUpload($fileUploadTagName, $timePlus, $exTime)
  {
    // $_FILES['documentFile']['name']
    // $_FILES['documentFile']['type']
    // $_FILES['documentFile']['size']
    // $_FILES['documentFile']['tmp_name']
    // if (file_exists($_FILES["file"]["name"]))
    $file_result = "";
    if ($_FILES["$fileUploadTagName"]['error'] > 0) {
      $file_result .= "no file uploaded or invalid file";
      $file_result .= "Error Code: " . $_FILES['$fileUploadTagName']['error'];
    } else {
      move_uploaded_file($_FILES["$fileUploadTagName"]['tmp_name'], "upload/doc/" . $exTime . $_FILES["$fileUploadTagName"]["name"]);
      $file_result .= "file upload successful";
    }
    return $exTime . $_FILES["$fileUploadTagName"]["name"];
  }

  function file_upload()
  {
    $upload_path = 'upload/doc/';
    $allowedExts = ["mp4", "wmv", "mpeg", "pdf", "doc", "docx"];
    //$extension = pathinfo($_FILES['uploadimage']['name'], PATHINFO_EXTENSION);
    $temp = explode(".", $_FILES["uploadvideo"]["name"]);
    $extension = end($temp);

    if (
      $_FILES["uploadvideo"]["type"] == "video/x-ms-wmv" ||
      $_FILES["uploadvideo"]["type"] == "video/x-ms-mp4" ||
      $_FILES["uploadvideo"]["type"] == "video/x-mpeg" ||
      $_FILES["uploadvideo"]["type"] == "video/x-matroska" ||
      $_FILES["uploadvideo"]["type"] == "video/mp4" &&
      in_array($extension, $allowedExts)
    ) {

      if (($_FILES["uploadvideo"]["size"]) <= 51242880) {
        $fileName = $_FILES["uploadvideo"]["name"]; // The file name
        $fileTmpLoc = $_FILES["uploadvideo"]["tmp_name"]; // File in the PHP tmp folder
        $fileType = $_FILES["uploadvideo"]["type"]; // The type of file it is
        $fileSize = $_FILES["uploadvideo"]["size"]; // File size in bytes
        $fileErrorMsg = $_FILES["uploadvideo"]["error"]; // 0 for false... and 1 for true

        /*
          if (!$fileTmpLoc) { // if file not chosen
          echo "ERROR: Please browse for a file before clicking the upload button.";
          exit();
          }
         */

        if (move_uploaded_file($fileTmpLoc, $upload_path . $fileName)) {

          echo "با موفقیت اپلود شد" . $_FILES["uploadvideo"]["name"];

        } else {
          echo "move_uploaded_file function failed";
        }
      } else {
        echo "File size exceeds 5 MB! Please try again!";
      }
    } else {
      echo "PHP! Not a video! "; //.$extension." ".$_FILES["uploadimage"]["type"];
    }
  }

}
