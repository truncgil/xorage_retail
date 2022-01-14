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
oturumAc();
$_SESSION['route'] = "stok-cikisi";
if(!oturumisset("urunler")) {
    $_SESSION['urunler'] = [];
}

if(getisset("q")) {
    
    array_push($_SESSION['urunler'],get("q"));
}
if(getisset("satir-sil")) {
    foreach($_SESSION['urunler'] AS $su_key => $su_value) {
        if(getesit("satir-sil",$su_value)) {
            echo $su_value;
            unset($_SESSION['urunler'][$su_key]);
        }
    }
    
    print2($_SESSION['urunler']);
    exit();
}
//print2($_SESSION['urunler']);
?>
<div class="row">


{{col("col-md-12","Yeni Sipariş Formu",10)}}
<?php 
if(getisset("siparis-ekle")) {
    $json = json_encode_tr($_POST);
    $dosya=post("oldfile");
    if($_FILES['file']['name']!="") {
        $dosya = upload("file",rand());
    }
    $siparis_ne = "";
    if(postisset("siparis-sil")) {
        $siparis_sorgu = db("siparis_grubu")->where("id",post("siparis-sil"));
        $siparis_ne = $siparis_sorgu->first();
        $siparis_sorgu->delete();
        $siparis_ne = json_encode_tr($siparis_ne);
    }
     $ekle_dizi = [
        'title' => post("urun_grubu"),
        'type' => post("siparis_numarasi"),
        "cover" => $dosya,
        'html' => post("html"),
        "json" => $json,
        "json2" => $siparis_ne,
        "qty" => post("sqty")
     ];
    $id = ekle($ekle_dizi,"siparis_grubu");
    $k = 0;
    foreach($_POST['urun'] AS $u) {
        ekle([
            'kid' => $id,
            "type" => $u,
            "qty" => $_POST['qty'][$k]
        ],"siparisler");
        $k++;
    }
    $varmi = db("contents")->where("title",post("urun_grubu"))
    ->where("json",$json)
    ->first();
    if(!$varmi) { // bunu şablon olarak kullanmak için ekledik
        ekle([
            'title' => post("urun_grubu"),
            "json" => $json,
           // "qty" => post("sqty"),
            "type" => "Ürün Grubu"
        ]);
    }

    unset($_SESSION['kaydet']);
    bilgi("Sipariş başarılı bir şekilde oluşturulmuştur");
} ?>

<?php $urun_gruplari = contents_to_array("Ürün Grubu");  ?>

<?php if(getisset("sablon")) {
    if(!getesit("sablon","")) {
        $sablon = c(get("sablon"));
        if($sablon) {
            $j = j($sablon->json);
            $satir_sayi = $j['satir_sayi'];
            ?>
            <script>
                
                $(function(){
                    $("#urun_grubu").val("{{$sablon->title}}");
                    $("#qty").val("{{$j['sqty']}}");
                    <?php 
                    $k = 0;
                    foreach($j['urun'] AS $urun) {
                        ?>
                        $(".urun-sec:eq({{$k}})").val("{{$urun}}");
                        $(".qty:eq({{$k}})").val("{{$j['qty'][$k]}}");
                        $("#stok-durum{{$k+1}}").load("?ajax=urun-stok-durum&id={{$urun}}",function(){
                            $("#stok-sonuc{{$k+1}}").html(eval($("#stok-durum{{$k+1}}").html())-eval("{{$j['qty'][$k]}}"));
                        });
                        <?php 
                        $k++;
                    } 
                    
                    ?>
                });
    
            </script>
    
         <?php 
        }
        
     }
} ?>
<?php 
$action = "?t=stok-cikisi&siparis-ekle";
if(getisset("siparis-duzenle")) {
   
    $sablon = db("siparis_grubu")->where("id",get("siparis-duzenle"))->first();
    $j = j($sablon->json);
    $satir_sayi = $j['satir_sayi'];
     ?>
     <script>
                
                $(function(){
                    $("#html").val("{{$sablon->html}}");
                    $("#siparis_numarasi").val("{{$sablon->type}}");
                    $("#urun_grubu").val("{{$sablon->title}}");
                    $("#qty").val("{{$j['sqty']}}");
                    <?php 
                    $k = 0;
                    foreach($j['urun'] AS $urun) {
                        ?>
                        $(".urun-sec:eq({{$k}})").val("{{$urun}}");
                        $(".qty:eq({{$k}})").val("{{$j['qty'][$k]}}");
                        $("#stok-durum{{$k+1}}").load("?ajax=urun-stok-durum&id={{$urun}}",function(){
                            $("#stok-sonuc{{$k+1}}").html(eval($("#stok-durum{{$k+1}}").html()));
                          //  $("#stok-sonuc{{$k+1}}").html(eval($("#stok-durum{{$k+1}}").html())-eval("{{$j['qty'][$k]}}"));
                        });
                        <?php 
                        $k++;
                    } 
                    
                    ?>
                });
    
            </script>
     <?php 
} ?>
<?php 
if(!getisset("sablon")) {
if(oturumisset("kaydet")) {
    $d = oturum("kaydet");
    if(!getisset("satir_sayi")) {
        if(isset($d['satir_sayi'])) {
            $satir_sayi = $d['satir_sayi'];
        }
        
    }
    if(isset($d['urun']))  { 
     
      ?>
      <script>
           $(function(){
                 $("#urun_grubu").val("{{$d['urun_grubu']}}");
                
             //    $("#siparis_numarasi").val("{{$d['siparis_numarasi']}}");
                 $("#qty").val("{{$d['sqty']}}");
                 <?php 
                 $k = 0;
                 foreach($d['urun'] AS $urun) {
                     if($d['qty'][$k]=="") $d['qty'][$k] = 0;
                     ?>
                     $(".urun-sec:eq({{$k}})").val("{{$urun}}");
                     $(".qty:eq({{$k}})").val("{{$d['qty'][$k]}}");
                     
                     $("#stok-durum{{$k+1}}").load("?ajax=urun-stok-durum&id={{$urun}}",function() {
                         $("#stok-sonuc{{$k+1}}").html(eval($("#stok-durum{{$k+1}}").html())-eval("{{$d['qty'][$k]}}"));
                     });
                     
                     <?php 
                     $k++;
                 } 
                 
                 ?>
             });
      </script> 
     <?php } ?>
     <?php 
} 
} //sablon isset 

?>
<form action="{{$action}}" enctype="multipart/form-data" method="post" id="siparis-ekle">
    {{csrf_field()}}
    <?php if(getisset("siparis-duzenle")) {
         ?>
         <input type="hidden" name="siparis-sil" value="{{get("siparis-duzenle")}}">

         <?php 
    } ?>
    <div class="row">
        <div class="col-md-9">
            {{e2("Şablondan Seç")}}
            <select name="" id="" onchange="location.href='?t=stok-cikisi&sablon='+$(this).val()" class="form-control select2">
                <option value="">Seçiniz</option>
                <?php foreach($urun_gruplari AS $u)  { 
                    $j = j($u->json);
                ?>
                <option value="{{$u->id}}" <?php if(getesit("sablon",$u->id)) echo "selected"; ?>>{{$u->title}} ({{$j['satir_sayi']}} Çeşit) ({{$j['sqty']}} Adet)</option> 
                <?php } ?>
            </select>
        </div>
        <div class="col-md-3">
            <?php if(getisset("siparis-duzenle")) {
                 ?>
                 <a href="{{picture2($sablon->cover,1024,0)}}" target="_blank">
                    <img src="{{picture2($sablon->cover,256,0)}}" alt="">
                 </a>

                 <?php 
            } ?> <br>
            {{e2("Görsel")}}
            <input type="file" name="file" id="" class="form-control">
        </div>
        <div class="col-md-2">
            {{e2("Ürün Çeşidi")}}
            <select name="satir_sayi" class="form-control satir_sayi" onchange="location.href='?satir_sayi='+$(this).val()" id="">
                <?php for($k=1;$k<=30;$k++)  { 
                  ?>
                 <option value="{{$k}}" <?php if($satir_sayi==$k) echo "selected"; ?>>{{$k}}</option> 
                 <?php } ?>
            </select>
        </div>
        <div class="col-md-2">
            {{e2("Sipariş Numarası")}}
            <input type="text" required name="siparis_numarasi" id="siparis_numarasi" class="form-control">
            
        </div>
        <div class="col-md-5">
            {{e2("Sipariş Ürün Grubu Adı")}}
            <input type="text" required name="urun_grubu" id="urun_grubu" class="form-control">
            
        </div>
        <div class="col-md-3">
            {{e2("Sipariş Adedi")}}
            <div class="input-group">
                <input type="number" class="form-control"  name="sqty" id="qty">
                <div class="btn btn-primary tumune-uygula" onclick="">{{e2("Tümüne Uygula")}}</div>
            </div>

        </div>
        
        <div class="col-md-12 mt-3">
           
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped " id="urunler">
                    <tr>
                        <th colspan="4" class="text-center">{{e2("Ürün Adı")}}</th>
                        
                        <th>İşlem</th>
                       
                    </tr>
                    <?php 
                   $k = 1;
                    foreach($_SESSION['urunler'] AS $u) { 
                        $urun = $urunler[$u];
                      ?>
                     <tr>
                         <td colspan="4" class="text-center">
                             <input type="hidden" data-id="{{$k}}" name="urun[]" value="{{$urun->id}}">
                             {{$urun->id}} <br>
                             {{$urun->title}} {{$urun->renk}} <br>
                             <div class="btn-group">
                                <div class="stok-durum btn btn-success" title="{{e2("Stok Durumu")}}" id="stok-durum{{$k}}">
                                    <?php 
                                    try {
                                        $stok_durum = stok_giris_sayim($urun->id)[$urun->id];
                                    } catch (\Throwable $th) {
                                        $stok_durum = 0;
                                    }
                                    
                                    echo $stok_durum; ?>
                                </div>
                                <input type="number" name="qty[]" id="qty{{$k}}" data-id="{{$k}}" value="" class="form-control qty">
                                <div class="stok-sonuc btn btn-danger" title="{{e2("Kalan Stok")}}" id="stok-sonuc{{$k}}"></div>
                             </div>
                             
                         </td>

                         <td><div class="btn btn-danger satir-sil" data-id="{{$urun->id}}"><i class="fa fa-trash"></i></div></td>
                         
                     </tr> 
                     <?php  $k++; } ?>
                </table>
            </div>
            <script>
                $(function(){
                    $(".qty").on("keyup",function(){
                        var stok_durum = $("#stok-durum"+$(this).attr("data-id"));
                        var hesap = eval(stok_durum.html()) - $(this).val();
                        $("#stok-sonuc"+$(this).attr("data-id")).html(hesap);
                    });
                    $(".satir-sil").on("click",function(){
                        $(this).parent().parent().remove();
                        var sayi = $(".satir_sayi").val();
                        sayi--;
                        //alert($(this).attr("data-id"));
                        $.get("?t=stok-cikisi&satir-sil="+$(this).attr("data-id"),function(d) {
                        //    alert(d);
                        });
                        $(".satir_sayi").val(sayi);
                        kaydet();
                    });
                    $(".satir-ekle").on("click",function(){
                        var sayi = $(".satir_sayi").val();
                        sayi++;
                        location.href="?t=stok-cikisi&satir_sayi="+sayi;
                        kaydet();
                    });
                    
                    $(".urun-sec").on("change",function(){
                        var bu = $(this);
                        var secici = $("#stok-durum"+bu.attr("data-id"));
                        secici.load("?ajax=urun-stok-durum&id="+bu.val(),function(d) {
                            var stok_durum = $("#stok-durum"+bu.attr("data-id"));
                            var qty_durum = $("#qty"+bu.attr("data-id"));
                            var hesap = eval(stok_durum.html()) - qty_durum.val();
                            $("#stok-sonuc"+bu.attr("data-id")).html(hesap);
                        });
                       
                        kaydet();
                    });
                    <?php if(getisset("q")) {
                         ?>
                            var stok_durum = $("#stok-durum{{get("q")}}");
                            var qty_durum = $("#qty{{get("q")}}");
                            var hesap = eval(stok_durum.html()) - qty_durum.val();
                            $("#stok-sonuc{{get("q")}}").html(hesap);
                         <?php 
                    } ?>
                    $(".satir_sayi").on("change",function(){
                        kaydet();
                    });
                    $(".form-control").on("keypress",function(){
                        kaydet();
                    });
                    $(".form-control").on("blur",function(){
                        kaydet();
                    });
                    $(".tumune-uygula").on("click",function(){
                        $('.qty').val($('#qty').val());
                        $(".qty").each(function(){
                            var id = $(this).attr("data-id");
                         //   console.log(id);
                            $("#stok-sonuc"+id).html($("#stok-durum"+id).html()-$(this).val());
                        });
                        kaydet();
                    });
                    function kaydet() {
                        var data = $("#siparis-ekle").serialize();
                        $.post("?t=stok-cikisi&kaydet",data);
                    }
                });
            </script>
        </div>
        <div class="col-md-12 mt-10">
            <textarea name="html" id="html" cols="30" rows="10" placeholder="{{e2("Açıklama")}}" class="form-control"></textarea>
        </div>
        <div class="col-md-12 mt-10 text-center">
            <button class="btn btn-primary">{{e2("Sipariş Oluştur")}}</button>
        </div>
        
    </div>

</form>
{{_col()}}
</div>