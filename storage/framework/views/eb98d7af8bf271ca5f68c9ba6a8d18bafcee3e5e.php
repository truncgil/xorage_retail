<?php 
$urunler = contents_to_array("Ürünler"); 
$musteriler = contents_to_array("Müşteriler"); 
$stok_cikis_sayim = stok_cikis_sayim();
$users = usersArray();
$user = u();
?>
<div class="content">
    <img src="<?php echo e(url("logo.svg")); ?>" style="    position: absolute;
    width: 300px;
    top: 20px;
    left: 20px;" class="yesprint" alt="">
    <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title"><i class="fa fa-<?php echo e($c->icon); ?>"></i> <?php echo e(e2("Filtrele")); ?></h3>
            </div>
            <div class="block-content">
                <form action="" method="get">
                    <div class="row">
                        <div class="col-md-4">
                            <?php echo e(e2("ÜRÜN")); ?> : 
                            <select name="urun" id="" class="form-control select2">
                                <option value="">Seçiniz</option>
                                <?php $sorgu = contents_to_array("Ürünler"); foreach($sorgu AS $m) { ?>
                                <option value="<?php echo e($m->id); ?>"><?php echo e($m->title); ?> <?php echo e($m->renk); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <?php echo e(e2("İŞLEM TARİHİ BAŞLANGIÇ")); ?> : 
                            <input type="date" name="date1" required value="<?php echo e(ed(get("date1"),date("Y-m-d"))); ?>" id="" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <?php echo e(e2("İŞLEM TARİHİ BİTİŞ")); ?> : 
                            <input type="date" name="date2" required value="<?php echo e(ed(get("date2"),date("Y-m-d"))); ?>" id="" class="form-control">
                        </div>
                        <div class="col-12 text-center mt-3">
                            <button class="btn btn-primary" name="filtre" value="ok">Filtrele</button>
                        </div>
                    </div>
                </form>
            </div>
            <script>
                $(function(){
                    <?php foreach($_GET AS $alan => $deger) {
                         ?>
                         $("[name='<?php echo e($alan); ?>']").val("<?php echo e($deger); ?>");
                         <?php 
                    } ?>
                });
            </script>
            

        </div>
        <div class="row">
            <?php if(getisset("filtre")) { 
              ?>
             
             <?php echo e(col("col-md-12","Geçmiş Stok Girişleri",3)); ?> 
  
  <?php $stoklar = db("stoklar");

  if(!getesit("lokasyon","")) {
      $stoklar = $stoklar->where("lokasyon",get("lokasyon"));
     
  }
  if(!getesit("urun","")) {
      $stoklar = $stoklar->where("type",get("urun"));
     
  }
  if(!getesit("date1","")) $stoklar = $stoklar->whereBetween("created_at",[get("date1"),get("date2")]);
  
  $stoklar = $stoklar->orderBy("id","desc");
 
  $stoklar = $stoklar->get(); ?>

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
   
  </div>
  <?php echo e(_col()); ?>

  <?php echo e(col("col-md-12","Geçmiş Stok Çıkışları",3)); ?> 
        <div class="table-responsive">
            <table class="table" id="excel">
                <tr>
                    <th><?php echo e(e2("ID")); ?></th>
                    <th><?php echo e(e2("ÜRÜN ADI")); ?></th>
                    <th><?php echo e(e2("MİKTAR")); ?></th>
                    <th><?php echo e(e2("TARİH")); ?></th>
                    <th><?php echo e(e2("PERSONEL")); ?></th>
                    <th><?php echo e(e2("SİPARİŞ NO")); ?></th>
                </tr>
                <?php $sorgu = db("siparisler");
                if(getisset("filtre")) {
                    if(!getesit("urun","")) $sorgu = $sorgu->where("type",get("urun"));
                    if(!getesit("date1","")) $sorgu = $sorgu->whereBetween("created_at",[get("date1"),get("date2")]);

                }
                $sorgu = $sorgu->orderBy("id","DESC")->simplePaginate(20); 
                
                foreach($sorgu AS $s)  { 
                    if(isset($urunler[$s->type]))  { 
                        $u = @$users[$stok->uid];
                     
                   ?>
                  <tr>
                      <td><?php echo e($s->id); ?></td>
                      <td><?php echo e($urunler[$s->type]->title); ?> <?php echo e($urunler[$s->type]->renk); ?></td>
                      <td><?php echo e($s->qty); ?></td>
                      <td><?php echo e(date("d.m.Y H:i",strtotime($s->created_at))); ?></td>
                      <td><?php echo e(@$u->name); ?> <?php echo e(@$u->surname); ?></td>
                      <td><?php echo e($s->kid); ?></td>
                  </tr>  
                     <?php } ?>
                 <?php } ?>
            </table>

            <?php echo e($sorgu->appends($_GET)->links()); ?>

        </div>     
    
    <?php echo e(_col()); ?>

             <?php } ?>
    </div>
    </div>
    
</div>
<script>
                $(function(){
                    $(".firma-sec").on("change",function(){
                        $(".detay").html("Yükleniyor...");
                        $.get("?ajax=siparisler",{
                            id : $(this).val()
                        },function(d){
                            $(".detay").html(d);
                        });
                        
                    });
                }); 
            </script>
           <?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/admin/type/raporlar.blade.php ENDPATH**/ ?>