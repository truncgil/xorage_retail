<?php 
$soru = explode(",",post("soru"));
foreach($soru AS $s) {
    $varmi = db("soru_atama")->where("user",post("uzman"))->where("soru_id",$s)->first();
    if(!$varmi) {
        ekle([
            "user"=>post("uzman"),
            "soru_id"=>$s        
        ],"soru_atama");
        bilgi("$s - Soru ataması yapılmıştır");
    } else {
        bilgi("$s - Soru ataması zaten yapıldığından işlem yapılmamıştır","danger");
    }
    
}
    
    
?>