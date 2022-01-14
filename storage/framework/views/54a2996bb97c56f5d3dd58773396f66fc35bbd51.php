<?php 
$user = u();
$urunler = contents_to_array("Ürünler"); 
$users = usersArray();
oturumAc();
$_SESSION['route'] = "stok-girisi";
?>
<div class="row">
    <?php echo e(col("col-md-12","Stok Girişi")); ?>

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
                            <option value="<?php echo e($u->id); ?>" <?php if(getesit("q",$u->id)) echo "selected"; ?>><?php echo e($u->id); ?> <?php echo e($u->title); ?> <?php echo e($u->renk); ?> / <?php echo e($u->title2); ?> / <?php echo e($u->grup); ?> </option>
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
                    <?php if(getisset("q")) {
                         ?>
                         $(".urun-sec").trigger("change");
                         <?php 
                    } ?>
                }); 
            </script>
        </form>
    <?php echo e(_col()); ?>


</div>
<strong><?php echo e(e2("Geçmiş Stok Girişleri")); ?></strong>
<div class="row">
        <?php $stok_cikislari = db("stoklar")->where("uid",u()->id)->orderBy("id","DESC")->simplePaginate(100); ?>
        <?php foreach($stok_cikislari AS $stok) {
            if(isset($urunler[$stok->type]))   { 
                $urun  = $urunler[$stok->type];
             
              ?>
              <?php echo e(col("col-md-12")); ?>

                    <div class="row">
                        <div class="col-4">
                            <div class="badge badge-info">
                            <?php echo e($stok->id); ?>

                            </div>
                        </div>
                        <div class="col-8 text-center">
                            <?php echo e($urun->title); ?> <?php echo e($urun->title2); ?>

                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-6">
                           <div class="badge badge-success">
                                <?php echo e(nf($stok->qty)); ?>

                           </div>
                        </div>
                        <div class="col-6 text-center">
                            <div class="badge badge-warning">
                                <?php echo e(date("d.m.y H:i",strtotime($stok->created_at))); ?>

                            </div>
                        
                        </div>
                    </div>
                    
 
              <?php echo e(_col()); ?>

              <?php  
              }
        } ?>
        <?php echo e($stok_cikislari->links()); ?>


</div><?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/admin/type/barkod/stok-girisi.blade.php ENDPATH**/ ?>