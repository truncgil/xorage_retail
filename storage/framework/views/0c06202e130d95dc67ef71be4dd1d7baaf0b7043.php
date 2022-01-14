<?php 
$user = u();
$urunler = contents_to_array("Ürünler"); 
$users = usersArray(); 
?>
<div class="content">
    <div class="row">
    <?php echo e(col("col-md-12","Yeni Stok Girişi",3)); ?>

    
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
            <?php echo e(csrf_field()); ?>

            <div class="row">
                <div class="col-md-2">
                    <div class="urun-bilgi"></div>
                </div>
                <div class="col-md-10">
                   
                    <label for="type"><?php echo e(e2("ÜRÜN:")); ?></label>
                    <select name="type" required class="form-control select2 urun-sec" required id="">
                            <option value="">Seçiniz</option>
                        <?php foreach($urunler AS $u) { ?>
                            <option value="<?php echo e($u->id); ?>"><?php echo e($u->id); ?> <?php echo e($u->title); ?> <?php echo e($u->renk); ?> / <?php echo e($u->title2); ?> / <?php echo e($u->grup); ?> </option>
                        <?php } ?>
                    </select>
                    
                    
                    
                    <label for="qty"><?php echo e(e2("MİKTAR")); ?></label>
                    <div class="input-group">
                        <input type="number" required  name="qty" step="any" class="form-control" value="0" id="qty">
                    </div>
                    <label for="lokasyon"><?php echo e(e2("LOKASYON")); ?></label>
                    <div class="input-group">
                        <?php $lokasyonlar = db("stoklar")->groupBy("lokasyon")->whereNotNull("lokasyon")->select("lokasyon")->get(); ?>
                        <select name="lokasyon" id="" class="form-control select2">
                            <option value=""><?php echo e(e2("Seçiniz")); ?></option>
                            <?php foreach($lokasyonlar AS $l)  { 
                              ?>
                             <option value="<?php echo e($l->lokasyon); ?>"><?php echo e($l->lokasyon); ?></option> 
                             <?php } ?>
                        </select>
                    </div>

                    <button class="btn btn-primary mt-10" type="submit"><?php echo e(e2("Ekle")); ?></button>
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
    <?php echo e(_col()); ?>


    <?php echo e(col("col-md-12","Filtrele")); ?>

    <form action="" method="get">
        <div class="row">
            
            <div class="col-md-12">
               
                <div class="input-group">
                    <select name="type" id="" class="form-control select2">
                        <option value=""><?php echo e(e2("TÜM ÜRÜNLER")); ?></option>
                        <?php foreach($urunler AS $u) { ?>
                                <option value="<?php echo e($u->id); ?>" <?php if(getesit("type",$u->id)) echo "selected"; ?>><?php echo e($u->id); ?> <?php echo e($u->title); ?> <?php echo e($u->renk); ?> / <?php echo e($u->title2); ?> / <?php echo e($u->grup); ?> </option>
                            <?php } ?>
                    </select>
                    <?php $lokasyonlar = db("stoklar")->groupBy("lokasyon")->whereNotNull("lokasyon")->select("lokasyon")->get(); ?>
                    <select name="lokasyon" id="" class="form-control select2">
                        <option value=""><?php echo e(e2("TÜM LOKASYONLAR")); ?></option>
                        <?php foreach($lokasyonlar AS $l)  { 
                            ?>
                            <option value="<?php echo e($l->lokasyon); ?>" <?php if(getesit("lokasyon",$l->lokasyon)) echo "selected"; ?>><?php echo e($l->lokasyon); ?></option> 
                            <?php } ?>
                    </select>
                    <?php $sayi = 20; 
                    if(getisset("sayi")) {
                        $sayi = get("sayi");
                    }
                    ?>
                    <input type="number" name="sayi" title="<?php echo e(e2("Bir sayfada gözükecek satır sayısı")); ?>" class="form-control" value="<?php echo e($sayi); ?>" id="">
                    <button class="btn btn-primary"><?php echo e(e2("FİLTRELE")); ?></button>
                </div>
            </div>
            
        </div>
    </form>
    <?php echo e(_col()); ?>

    <?php echo e(col("col-md-12","Geçmiş Stok Girişleri",3)); ?> 
  
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
        <input type="text" name="q" placeholder="<?php echo e(e2("Ara...")); ?>" value="<?php echo e(get("q")); ?>" id="" class="form-control">

    </form>
    <div class="table-responsive">
        <table class="table" id="excel">
            <tr>
                <th><?php echo e(e2("STOK NO")); ?></th>
                
                <th><?php echo e(e2("BARKOD")); ?></th>
                <th><?php echo e(e2("ÜRÜN ADI")); ?></th>
                <th><?php echo e(e2("MİKTAR")); ?></th>
                <th><?php echo e(e2("LOKASYON")); ?></th>
                <th><?php echo e(e2("İŞLEM TARİHİ")); ?></th>
                <th><?php echo e(e2("PERSONEL")); ?></th>
                <th><?php echo e(e2("DURUM")); ?></th>
                <th><?php echo e(e2("İŞLEM")); ?></th>
            </tr>
            <?php foreach($stoklar AS $stok) { 
                $j = j($stok->json);
                if(isset($urunler[$stok->type]))  { 
                 
                 $urun = $urunler[$stok->type];
                 
                 $u = @$users[$stok->uid];
                 ?>
             <tr id="t<?php echo e($stok->id); ?>">
                 <td><?php echo e($stok->id); ?></td>
                 <td><?php echo e($stok->type); ?></td>
                 <td><?php echo e($urun->title); ?></td>
                 
                 <td><?php echo e($stok->qty); ?></td>
                 <td><?php echo e($stok->lokasyon); ?></td>
                 <td><?php echo e(date("d.m.Y H:i",strtotime($stok->created_at))); ?></td>
                 <td><?php echo e($u->name); ?> <?php echo e($u->surname); ?></td>
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
                          <a href="?sil=<?php echo e($stok->id); ?>" ajax="#t<?php echo e($stok->id); ?>" teyit="<?php echo e(e2("Bu stok bilgisini silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!")); ?>" class="btn btn-danger"><i class="fa fa-times"></i></a>
                          <?php 
                     } ?>
                     <a href="?ajax=print-stok&id=<?php echo e($stok->id); ?>" target="_blank" class="btn btn-success d-none">
                         <i class="fa fa-print"></i>
                     </a>
                 </td>
             </tr> 
                 <?php } ?>
            <?php } ?>
        </table>
        <?php echo e($stoklar->appends(request()->except(['page','_token']))->links()); ?>

    </div>
    <?php echo e(_col()); ?>

    </div>
</div>

<?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/admin/type/stok-girisleri.blade.php ENDPATH**/ ?>