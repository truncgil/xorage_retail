<?php
oturumAc();
 $satir_sayi = 10;

 if(getisset("satir_sayi")) {
   $satir_sayi = get("satir_sayi");
   $_SESSION['kaydet']['satir_sayi'] = $satir_sayi; 
}
if(getisset("yeni-siparis")) {
    unset($_SESSION['kaydet']);

} 
if(getisset("kaydet")) {
   // print2($_POST);
    unset($_SESSION['kaydet']);
    $_SESSION['kaydet'] = $_POST;
    exit();
}

$urunler = contents_to_array("Ürünler");

?>

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
        $siparis_alt = db("siparisler")->where("kid",post("siparis-sil"))->delete();
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
$action = "?siparis-ekle";
if(getisset("siparis-duzenle")) {
   
    $sablon = db("siparis_grubu")->where("id",get("siparis-duzenle"))->first();
    $j = j($sablon->json);
    $satir_sayi = $j['satir_sayi'];
    if(getisset("satir_sayi")) $satir_sayi = get("satir_sayi");
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
                        $(".qty2:eq({{$k}})").val("{{$j['qty'][$k]}}");
                        $("#stok-durum{{$k+1}}").load("?ajax=urun-stok-durum&id={{$urun}}",function(){
                            $("#stok-sonuc{{$k+1}}").html(eval($("#stok-durum{{$k+1}}").html()));
                            $("#stok-sonuc{{$k+1}}").html(eval($("#stok-durum{{$k+1}}").html())+eval("{{$j['qty'][$k]}}")-eval("{{$j['qty'][$k]}}"));
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
if(!getisset("siparis-duzenle")) {
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
} //duzenle isset 
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
        <div class="col-md-3">
            <a href="?yeni-siparis" class="btn btn-primary"><i class="fa fa-plus"></i> {{e2("Yeni Sipariş Oluştur")}}</a>
        </div>
        <div class="col-md-6">
            {{e2("Şablondan Seç")}}
            <select name="" id="" onchange="location.href='?sablon='+$(this).val()" class="form-control select2">
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
                        <td>{{e2("Ürün Adı")}}</td>
                        <td>{{e2("Adet")}}</td>
                        <td>{{e2("Stok Durumu")}}</td>
                        <td>{{e2("Stok Kalan")}}</td>
                        <td>İşlem</td>
                       
                    </tr>
                    <?php 
                   
                    for($k=1;$k<=$satir_sayi;$k++)  { 
                      ?>
                     <tr>
                         <td>
                             <select name="urun[]"  style="width:200px;max-width:200px;" required data-id="{{$k}}" class="select2 urun-sec">
                                 <option value="">{{e2("Seçiniz")}}</option>
                                 <?php foreach($urunler AS $urun)  { 
                                  ?>
                                  <option value="{{$urun->id}}">{{$urun->title}} {{$urun->renk}} 
                                     
                                  </option> 
                                  <?php } ?>
                             </select>
                         </td>
                         <td>
                             <input type="number" name="qty[]" id="qty{{$k}}" data-id="{{$k}}" class="form-control qty">
                             <?php if(getisset("siparis-duzenle"))  { 
                               ?>
                              <input type="hidden" id="qty2{{$k}}" data-id="{{$k}}" class="form-control qty2"> 
                              <?php } ?>
                         </td>
                         <td>
                             <div class="stok-durum" id="stok-durum{{$k}}"></div>

                             
                         </td>
                         <td>
                            <div class="stok-sonuc text-red" id="stok-sonuc{{$k}}"></div>
                         </td>
                         <td><div class="btn btn-danger satir-sil"><i class="fa fa-trash"></i></div></td>
                         
                     </tr> 
                     <?php } ?>
                </table>
                <div class="btn btn-primary satir-ekle"><i class="fa fa-plus"></i> {{e2("Yeni Satır Ekle")}}</div>
            </div>
            <script>
                $(function(){
                    $(".qty").on("keyup",function(){
                        var id = $(this).attr("data-id");
                        var stok_durum = $("#stok-durum"+id);
                        <?php 
                        if(getisset("siparis-duzenle")) {
 ?>
 var eski_stok = $("#qty2"+id);
 var hesap = eval(stok_durum.html()) + eval(eski_stok.val()) - $(this).val(); 
 <?php  
                        } else { 
                         
                         ?>
                         var hesap = eval(stok_durum.html()) - $(this).val(); 
                         <?php } ?>

                        $("#stok-sonuc"+$(this).attr("data-id")).html(hesap);
                    });
                    $(".satir-sil").on("click",function(){
                        $(this).parent().parent().remove();
                        var sayi = $(".satir_sayi").val();
                        sayi--;
                        $(".satir_sayi").val(sayi);
                        kaydet();
                    });
                    $(".satir-ekle").on("click",function(){
                        var sayi = $(".satir_sayi").val();
                        sayi++;
                        location.href="?<?php if(getisset("siparis-duzenle")) echo "siparis-duzenle={$_GET['siparis-duzenle']}&"; ?>satir_sayi="+sayi;
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
                            var index = id-1;
                            console.log($(".qty2:eq("+index+")").val());
                         <?php if(getisset("siparis-duzenle")) {
                             ?>
                              $("#stok-sonuc"+id).html(eval($("#stok-durum"+id).html())+eval($(".qty2:eq("+index+")").val())-$(this).val()); 
                             <?php 
                         } else  { 
                           ?>
                             $("#stok-sonuc"+id).html($("#stok-durum"+id).html()-$(this).val()); 
                          <?php } ?>
                        });
                        kaydet();
                    });
                    function kaydet() {
                        var data = $("#siparis-ekle").serialize();
                        $.post("?kaydet",data);
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