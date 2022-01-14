<?php 
$user = u();
$urunler = contents_to_array("Ürünler"); 
$users = usersArray();

?>
<div class="content">
    <div class="row">
        
        

    <?php echo e(col("col-md-12","Filtrele")); ?>

    <form action="" method="get">
                    <div class="row">
                       
                        <div class="col-md-6">
                            <?php echo e(e2("ÜRÜN")); ?> : 
                            <select name="urun" id="" class="form-control select2">
                                <option value=""><?php echo e(e2("TÜMÜ")); ?></option>
                                <?php $sorgu = contents_to_array("Ürünler"); foreach($sorgu AS $m) { ?>
                                <option value="<?php echo e($m->id); ?>" <?php if(getesit("urun",$m->id)) echo "selected"; ?>><?php echo e($m->title); ?> <?php echo e($m->renk); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <?php echo e(e2("PERSONEL")); ?> : 
                            <select name="uid" id="" class="form-control select2">
                                <option value=""><?php echo e(e2("TÜMÜ")); ?></option>
                                <?php foreach($users AS $us) { ?>
                                <option value="<?php echo e($us->id); ?>" <?php if(getesit("uid",$us->id)) echo "selected"; ?>><?php echo e($us->name); ?> <?php echo e($us->surname); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <?php echo e(e2("İŞLEM TARİHİ BAŞLANGIÇ")); ?> : 
                            <input type="date" name="date1"  value="<?php echo e(get("date1")); ?>" id="" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <?php echo e(e2("İŞLEM TARİHİ BİTİŞ")); ?> : 
                            <input type="date" name="date2"  value="<?php echo e(get("date2")); ?>" id="" class="form-control">
                        </div>
                       
                       
                        <div class="col-md-12 text-center">
                            <button class="btn btn-primary mt-10" name="filtre" value="ok"><?php echo e(e2("FİLTRELE")); ?></button>
                        </div>
                    </div>
                    
                   
                    
                    
                    

                </form>
    <?php echo e(_col()); ?>

    <?php echo e(col("col-md-12","Geçmiş Barkod Stok Çıkışları",3)); ?> 
        <div class="table-responsive">
            <table class="table" id="excel">
                <tr>
                    <td><?php echo e(e2("ID")); ?></td>
                    <td><?php echo e(e2("Ürün Adı")); ?></td>
                    <td><?php echo e(e2("Miktar")); ?></td>
                    <td><?php echo e(e2("Tarih")); ?></td>
                    <td><?php echo e(e2("Personel")); ?></td>
                    <td><?php echo e(e2("Sipariş No")); ?></td>
                </tr>
                <?php $sorgu = db("stok_barkod_cikislari");
                if(getisset("filtre")) {
                    if(!getesit("urun","")) $sorgu = $sorgu->where("type",get("urun"));
                    if(!getesit("uid","")) $sorgu = $sorgu->where("uid",get("uid"));
                    if(!getesit("date1","")) $sorgu = $sorgu->whereBetween("created_at",[get("date1"),get("date2")]);

                }
                $sorgu = $sorgu->orderBy("id","DESC")->simplePaginate(20); 
                
                foreach($sorgu AS $s)  { 
                    $personel = @$users[$s->uid];
                    if(isset($urunler[$s->type]))  { 
                     
                   ?>
                  <tr>
                      <td><?php echo e($s->id); ?></td>
                      <td><?php echo e($urunler[$s->type]->title); ?> <?php echo e($urunler[$s->type]->renk); ?></td>
                      <td><?php echo e($s->qty); ?></td>
                      <td><?php echo e(date("d.m.Y H:i",strtotime($s->created_at))); ?></td>
                      <td><?php echo e(@$personel->name); ?> <?php echo e(@$personel->surname); ?></td>
                      <td><?php echo e($s->kid); ?></td>
                  </tr>  
                     <?php } ?>
                 <?php } ?>
            </table>

            <?php echo e($sorgu->appends($_GET)->links()); ?>

        </div>     
    
    <?php echo e(_col()); ?>

    </div>
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

<?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/admin/type/barkod-cikislari.blade.php ENDPATH**/ ?>