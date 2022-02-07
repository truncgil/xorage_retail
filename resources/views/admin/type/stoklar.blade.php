<?php 
$user = u();
$urunler = contents_to_array("Ürünler"); 
$users = usersArray();
?>
<div class="content">
    <div class="row">
    {{col("col-md-12","Yeni Stok Girişi",3)}}
    
    <?php 
    if(getisset("sil")) {
        db("stoklar")->where("id",get("sil"))
        ->delete();
        echo "ok";
        exit();
    }
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
    } ?>
   
        <form action="?ekle" method="post" class="">
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
                            <option value="{{$u->id}}">{{$u->id}} {{$u->title}} {{$u->renk}} / {{$u->title2}} / {{$u->grup}} 

                            {{str_slug($urun->slug," ")}}
                            </option>
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
                }); 
            </script>
        </form>
    {{_col()}}

    {{col("col-md-12","Filtrele")}}
    <form action="" method="get">
        <div class="row">
            
            <div class="col-md-12">
               
                <div class="input-group">
                    <select name="type" id="" class="form-control select2">
                        <option value="">{{e2("TÜM ÜRÜNLER")}}</option>
                        <?php foreach($urunler AS $u) { ?>
                                <option value="{{$u->id}}" <?php if(getesit("type",$u->id)) echo "selected"; ?>>{{$u->id}} {{$u->title}} {{$u->renk}} / {{$u->title2}} / {{$u->grup}} </option>
                            <?php } ?>
                    </select>
                    <?php $lokasyonlar = db("stoklar")->groupBy("lokasyon")->whereNotNull("lokasyon")->select("lokasyon")->get(); ?>
                    <select name="lokasyon" id="" class="form-control select2">
                        <option value="">{{e2("TÜM LOKASYONLAR")}}</option>
                        <?php foreach($lokasyonlar AS $l)  { 
                            ?>
                            <option value="{{$l->lokasyon}}" <?php if(getesit("lokasyon",$l->lokasyon)) echo "selected"; ?>>{{$l->lokasyon}}</option> 
                            <?php } ?>
                    </select>
                    <?php $sayi = 20; 
                    if(getisset("sayi")) {
                        $sayi = get("sayi");
                    }
                    ?>
                    <input type="number" name="sayi" title="{{e2("Bir sayfada gözükecek satır sayısı")}}" class="form-control" value="{{$sayi}}" id="">
                    <button class="btn btn-primary">{{e2("FİLTRELE")}}</button>
                </div>
            </div>
            
        </div>
    </form>
    {{_col()}}
    {{col("col-md-12","Geçmiş Stok Girişleri",3)}} 
  
    <?php $stoklar = db("stoklar");
    if(getisset("q")) {
       // $stoklar = where("slug")
       $deger = "%".trim(get("q"))."%";
       $q = get("q");
       
      
        
        $urunlerdb = db("contents")->where("title","like","%{$_GET['q']}%")->where("type","Ürünler")->get();
        if($urunlerdb) {
            $urunlerdizi = array();
            foreach($urunlerdb AS $udb) {
                array_push($urunlerdizi,$udb->id);
            }
        //    print2($urunlerdizi);
            $stoklar = $stoklar->whereIn("type",$urunlerdizi);
        }
        
        $stoklar = $stoklar->orwhere(function($query) use($deger,$q){
               $query->orWhere("slug",$q);
               $query->orWhere("json","like",$deger);
        });
        
        

    }
    if(!getesit("lokasyon","")) {
        $stoklar = $stoklar->where("lokasyon",get("lokasyon"));
       
    }
    if(!getesit("type","")) {
        $stoklar = $stoklar->where("type",get("type"));
       
    }
    
    $stoklar = $stoklar->orderBy("id","desc");
   
    $stoklar = $stoklar->simplePaginate($sayi); ?>
    <form action="" method="get">
        <input type="text" name="q" placeholder="{{e2("Ara...")}}" value="{{get("q")}}" id="" class="form-control">

    </form>
    <div class="table-responsive">
        <table class="table" id="excel">
            <tr>
                <th>{{e2("STOK NO")}}</th>
                
                <th>{{e2("BARKOD")}}</th>
                <th>{{e2("ÜRÜN ADI")}}</th>
                <th>{{e2("MİKTAR")}}</th>
                <th>{{e2("LOKASYON")}}</th>
                <th>{{e2("İŞLEM TARİHİ")}}</th>
                <th>{{e2("PERSONEL")}}</th>
                <th>{{e2("DURUM")}}</th>
                <th>{{e2("İŞLEM")}}</th>
            </tr>
            <?php foreach($stoklar AS $stok) { 
                $j = j($stok->json);
                $urun = $urunler[$stok->type];
                
                $u = @$users[$stok->uid];
                ?>
            <tr id="t{{$stok->id}}">
                <td>{{$stok->id}}</td>
                <td>{{$stok->type}}</td>
                <td>{{$urun->title}}</td>
                
                <td>{{$stok->qty}}</td>
                <td>{{$stok->lokasyon}}</td>
                <td>{{date("d.m.Y H:i",strtotime($stok->created_at))}}</td>
                <td>{{$u->name}} {{$u->surname}}</td>
                <td><?php if($stok->cikis!="") {
                     ?>
                     <div class="badge badge-success">
                        <i class="fa fa-check"></i>
                     </div>
                     <?php 
                } ?></td>
                <td>
                    <?php if($user->level=="Admin") {
                         ?>
                         <a href="?sil={{$stok->id}}" ajax="#t{{$stok->id}}" teyit="{{e2("Bu stok bilgisini silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!")}}" class="btn btn-danger"><i class="fa fa-times"></i></a>
                         <?php 
                    } ?>
                    <a href="?ajax=print-stok&id={{$stok->id}}" target="_blank" class="btn btn-success d-none">
                        <i class="fa fa-print"></i>
                    </a>
                </td>
            </tr>
            <?php } ?>
        </table>
        {{$stoklar->appends(request()->except(['page','_token']))->links()}}
    </div>
    {{_col()}}
    </div>
</div>

