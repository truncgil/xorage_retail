<?php 
echo "file data : ";
print2($_FILES);
echo "post data: ";
print2($_POST);
echo "get data: ";
print2($_GET);
if(!postisset("title")) {
    echo "title alanı zorunludur"; 
    exit();
}
if(!postisset("kid")) {
    echo "kid (kategori) alanı zorunludur"; 
    exit();
}
if(!isset($_FILES['file'])) {
    echo "file alanı zorunludur"; 
    exit();
}
if($_FILES['file']['name']=="") {
    echo "file alanı zorunludur"; 
    exit();
}
if($_FILES['file']['type']!="application/x-zip-compressed") {
    echo "dosya zip formatında olmalıdır"; 
    exit();
}
$post = $_POST;
unset($post['email']);
unset($post['password']);
$post['type'] = "Modeller";
$post['slug'] = str_slug($post['title']).rand();

$path = "storage/app/files/{$post['slug']}/";

$file = upload("file",$post['slug']);
$zip = new ZipArchive;
$res = $zip->open($file);
if ($res === TRUE) {
    $zip->extractTo($path);
    $zip->close();
    echo 'Zip Extract Ok';
    $file = implode(",",glob($path."*.*"));
} else {
    echo 'Zip Extract False';
}
$post['files'] = $file;


ekle([
    'title' => $post['title'],
    'files' => $file,
    'kid' => $post['kid'],
    'type' => "Modeller",
    'slug' => $post['slug'],
    'json' => post("json")
],"contents");
print2($post);
echo "İşlem başarılı";
?>