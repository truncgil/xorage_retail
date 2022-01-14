<?php
oturumAc();
 $satir_sayi = 10;
 if(getisset("satir_sayi")) {
   $satir_sayi = get("satir_sayi");
} 
if(getisset("kaydet")) {
   // print2($_POST);
    unset($_SESSION['kaydet']);
    $_SESSION['kaydet'] = $_POST;
    exit();
}

$urunler = contents_to_array("Ürünler");
$siparisler = db("siparis_grubu")->orderBy("id","DESC")->take(100)->get();
oturumAc();
$_SESSION['route'] = "stok-cikisi";
if(!oturumisset("urunler")) {
    $_SESSION['urunler'] = [];
}

?>
<div class="row">


{{col("col-md-12","Stok Çıkışı",10)}}
<?php 
if(getisset("ekle")) {
    $post = $_POST;
    unset($post['_token']);
    $urun = db("contents")->where("id",post("type"))->first();
    
    if($urun) {
        if($urun->grup=="SARF") {
            ekle($post,"siparisler");
            bilgi("Sarf çıkışı oluşturulmuştur");
        }
    }
    if(!postesit("kid","")) {
        ekle($post,"stok_barkod_cikislari");
        bilgi("Stok çıkış bilgisi oluşturuldu");
    } else {
        bilgi("İlgili sipariş eklenilmediğinden stok çıkış bilgisi oluşturulamadı","danger");
    }
    
}
?>
 <form action="?t=stok-cikisi&ekle" method="post">
     @csrf
        <div class="col-md-12">
            <div class="urun-bilgi"></div>
        </div>

        <div class="col-md-12">
            {{e2("İLGİLİ SİPARİŞ")}} 
            <?php // print2($siparisler); ?>
            <select name="kid" id="" class="form-control">
                <option value="">{{e2("SARF ÇIKIŞI")}}</option>
                <?php foreach($siparisler AS $s)  { 
                    $j = j($s->json);
                  ?>
                 <option value="{{$s->id}}">{{$s->id}} {{$s->type}} </option> 
                 <?php } ?>
            </select>
            <label for="type">{{e2("ÜRÜN:")}}</label>
            <select name="type" required class="form-control select2 urun-sec" required id="">
                    <option value="">Seçiniz</option>
                <?php foreach($urunler AS $u) { ?>
                    <option value="{{$u->id}}" <?php if(getesit("q",$u->id)) echo "selected"; ?>>{{$u->id}} {{$u->title}} {{$u->renk}} / {{$u->title2}} / {{$u->grup}} </option>
                <?php } ?>
            </select>
            
            
            
            <label for="qty">{{e2("MİKTAR")}}</label>
            <div class="input-group">
                <input type="number" required  name="qty" step="any" class="form-control" value="0" id="qty">
            </div>
            <br>
            <button class="btn btn-primary">Stok Çıkışını Yap</button>
        </div>

        <script>
            $(function(){
                
                $(".urun-sec").on("change",function(){
                    $(".urun-bilgi").html("Yükleniyor...");
                    $.get("?ajax=urun-bilgi",{
                        id : $(this).val()
                    },function(d){
                        $(".urun-bilgi").html(d);
                    });
                    
                });
                <?php if(getisset("q")) {
                        ?>
                        $(".urun-sec").trigger("change");
                        <?php 
                } ?>
            }); 
        </script>
 </form>
{{_col()}}
</div>