<?php 

$models = db("contents")->where("type","Modeller")
->whereNotNull("files")
->whereNotNull("slug");
if(postisset("model_id")) {
    $models = $models->where("id",post("model_id"));
}
$models = $models->get();
$json = array();
$k = 0;
foreach($models AS $m) {
    $files = explode(",",$m->files);
   $json[$k]['model_id'] = $m->id;
   $json[$k]['title'] = $m->title;
   $json[$k]['category'] = $m->kid;
   $json[$k]['models'] = json_decode($m->json,true);
   $json[$k]['files'] = $files;
   $zip = new ZipArchive;
   $path = "storage/app/files/{$m->slug}/";
   $zip_file = "$path{$m->slug}.zip";
 //  echo $zip_file; exit();
    @unlink($zip_file);
    if(count($files)>0) {
        if ($zip->open($zip_file, ZipArchive::CREATE) === TRUE)
        {
            foreach($files AS $f) {
               // echo $f; exit();
               if(file_exists($f)) {
                   $file_name = str_replace($path,"",$f);
                $zip->addFile($f,$file_name);
               }
               
            }
        
            // All files are added, so close the zip file.
            @$zip->close();
            $json[$k]['zip_file'] = $zip_file;
        }
    }
$k++;
}
echo json_encode_tr($json);
?>