<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Contents;

use Illuminate\Support\Facades\DB;

$p = $request->all();

	?>
<?php 
$u = u();
$varmi = db("ogretmen_cevap")
->where("ogretmen_id",$u->id)
->where("soru_id",post("soru_id"))
->first();
if(!$varmi) {
    ekle([
        "ogretmen_id" => $u->id,
        "soru_id" => post("soru_id"),
        "cevap" => post("cevap"),
        "tak_duzey" => post("tak_duzey")
    ],"ogretmen_cevap");
} else {
    db("ogretmen_cevap")
    ->where("soru_id",post("soru_id"))
    ->where("ogretmen_id",$u->id)
    ->where("uid",$u->id) 
    ->update([
        "cevap" => post("cevap"),
        "tak_duzey" => post("tak_duzey")
    ]);
}
?>