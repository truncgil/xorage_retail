<?php 
//phpinfo(); exit();
error_reporting(E_ALL);
//include("../public_html/pdftojpg/vendor/autoload.php");
if(isset($_GET['ok'])) {
   // @mkdir("uploads",true);
    $target_dir = "/";
    $target_file = basename("test.pdf");
    echo $target_file;
    //print_r($_FILES);
    if (move_uploaded_file($_FILES["upload"]["tmp_name"], $target_file)) {
        $pdf = new Spatie\PdfToImage\Pdf($target_file);
        $pdf->saveImage("test.jpg");
        echo "ok";
    } else {
    echo "Sorry, there was an error uploading your file.";
    }

        
  
    echo $uploadOk;
   
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="?ok" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
        <input type="file" name="upload" required id="">
        <button>Send</button>
    </form>
</body>
</html>