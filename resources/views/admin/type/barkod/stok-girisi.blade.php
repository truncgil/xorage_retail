<?php 
$user = u();
$urunler = contents_to_array("Ürünler"); 
$users = usersArray();
oturumAc();
$_SESSION['route'] = "stok-girisi";
?>
<div class="row">
    {{col("col-md-12","Stok Girişi")}}
    <?php 
    if(getisset("ekle")) {
        $post = $_POST;
        $qty = $post['qty'];
        
        $type = $post['type'];

       
        $son_id = db("stoklar")->orderBy("id","DESC")->first();
       // $barcode = date("Ymdhi").$son_id->id;
        ekle([
            "type" => $type,
            "lokasyon" => post("lokasyon"),
        //    "slug" => $barcode,
            "qty" => $qty
        ],"stoklar");
        bilgi("Stok girişi başarıyla oluşturuldu");
    } 
    ?>
       <form action="?t=stok-girisi&ekle" method="post" class="">
            {{csrf_field()}}
            <div class="row">
                <div class="col-md-2">
                    <div class="urun-bilgi"></div>
                </div>
                <div class="col-md-10">
                   
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
                    <label for="lokasyon">{{e2("LOKASYON")}}</label>
                    <div class="input-group">
                        <?php $lokasyonlar = db("stoklar")->groupBy("lokasyon")->whereNotNull("lokasyon")->select("lokasyon")->get(); ?>
                        <select name="lokasyon" id="" class="form-control select2">
                            <option value="">{{e2("Seçiniz")}}</option>
                            <?php foreach($lokasyonlar AS $l)  { 
                              ?>
                             <option value="{{$l->lokasyon}}">{{$l->lokasyon}}</option> 
                             <?php } ?>
                        </select>
                    </div>

                    <button class="btn btn-primary mt-10" type="submit">{{e2("Ekle")}}</button>
                </div>
                
                
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
<strong>{{e2("Geçmiş Stok Girişleri")}}</strong>
<div class="row">
        <?php $stok_cikislari = db("stoklar")->where("uid",u()->id)->orderBy("id","DESC")->simplePaginate(100); ?>
        <?php foreach($stok_cikislari AS $stok) {
            if(isset($urunler[$stok->type]))   { 
                $urun  = $urunler[$stok->type];
             
              ?>
              {{col("col-md-12")}}
                    <div class="row">
                        <div class="col-4">
                            <div class="badge badge-info">
                            {{$stok->id}}
                            </div>
                        </div>
                        <div class="col-8 text-center">
                            {{$urun->title}} {{$urun->title2}}
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-6">
                           <div class="badge badge-success">
                                {{nf($stok->qty)}}
                           </div>
                        </div>
                        <div class="col-6 text-center">
                            <div class="badge badge-warning">
                                {{date("d.m.y H:i",strtotime($stok->created_at))}}
                            </div>
                        
                        </div>
                    </div>
                    
 
              {{_col()}}
              <?php  
              }
        } ?>
        {{$stok_cikislari->links()}}

</div>