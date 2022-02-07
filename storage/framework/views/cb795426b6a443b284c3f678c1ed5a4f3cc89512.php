
<?php echo e(col("col-md-12","Filtrele")); ?>

<a name="liste"></a>
    <form action="#liste" method="get">
                    <div class="row">
                       <div class="col-auto">
                           <?php echo e(e2("SİPARİŞ NUMARASI")); ?>: 
                            <input type="text" name="siparis_id" value="<?php echo e(get("siparis_id")); ?>" id="" class="form-control">
                       </div>
                        <div class="col-auto">
                            <?php echo e(e2("ÜRÜN GRUBU")); ?> : <br>
                            <select name="urun" id="" class="form-control select2">
                                <option value=""><?php echo e(e2("TÜMÜ")); ?></option>
                                <?php $sorgu = db("contents")->where("type","Ürün Grubu")->groupBy("title")
                                ->whereNotNull("title")
                                ->orderBy("title","ASC")
                                ->get(); 
                                
                                foreach($sorgu AS $m) { ?>
                                <?php if($m->title!="")  { 
                                  ?>
                                 <option value="<?php echo e($m->title); ?>" <?php if(getesit("urun",$m->title)) echo "selected"; ?>><?php echo e($m->title); ?></option> 
                                 <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-auto">
                            <?php echo e(e2("ÜRÜN")); ?> :  <br>
                            <select name="urun2" id="" class="form-control select2">
                                <option value=""><?php echo e(e2("TÜMÜ")); ?></option>
                                <?php $sorgu = contents_to_array("Ürünler"); foreach($sorgu AS $m) { ?>
                                <option value="<?php echo e($m->id); ?>" <?php if(getesit("urun2",$m->id)) echo "selected"; ?>>
                                <?php echo e($m->title); ?> <?php echo e($m->renk); ?>  <?php echo e(str_slug($m->slug," ")); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-auto">

                                    <?php echo e(e2("DURUM")); ?> :  <br>
                            <select name="durum" id="" class="form-control">
                                <option value=""><?php echo e(e2("TÜMÜ")); ?></option>
                                <?php foreach(siparis_durumlari() AS $d)  { 
                                  ?>
                                 <option value="<?php echo e($d); ?>" <?php if(getesit("durum",$d)) echo "selected"; ?>><?php echo e($d); ?></option> 
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

<?php echo e(col("col-md-12","Oluşturulan Siparişler")); ?>

<?php if(getisset("siparis-sil")) {
    db("siparis_grubu")->where("id",get("siparis-sil"))->delete();
    db("siparisler")->where("kid",get("siparis-sil"))->delete();
    bilgi(get("siparis-sil"). " nolu sipariş silinmiştir");
} ?>
<?php if(getisset("tamamlandi")) {
    $data = $_POST;
    db("siparis_grubu")->where("id",get("tamamlandi"))
    ->update([
        "tamamlandi" => json_encode_tr($data)
    ]);
    print_r($_POST);
    exit();
} ?>
<script>
    $(function(){
        $(".tamamlandi").on("click",function(){
            var id = $(this).attr("data-id");
            var form = $("#form"+id);
            data = form.serialize();
            $.ajax({
                type: "POST",
                url: "?tamamlandi="+id,
                data: data,
                dataType: "json",
                success: function(data) {
                    //var obj = jQuery.parseJSON(data); if the dataType is not specified as json uncomment this
                    // do what ever you want with the server response
                },
                error: function() {
                  
                }
            });
            //console.log(form.serialize());
        });
    });
</script>
    <div class="table-responsive">
        <table  class="table" id="excel">
            <tr>
                <td><?php echo e(e2("Görsel")); ?></td>
                <td><?php echo e(e2("ID")); ?></td>
                <td><?php echo e(e2("Sipariş Numarası")); ?></td>
                <td><?php echo e(e2("Sipariş Ürün Grubu Adı")); ?></td>
                <td><?php echo e(e2("Sipariş İçeriği")); ?></td>
                <td><?php echo e(e2("Sipariş Miktarı")); ?></td>
                <td><?php echo e(e2("Tarih")); ?></td>
                <td><?php echo e(e2("Açıklama")); ?></td>
                <td><?php echo e(e2("Durum")); ?></td>
                <td><?php echo e(e2("İşlem")); ?></td>
            </tr>
            <?php $siparisler = db("siparis_grubu");
            if(getisset("filtre")) {
                if(!getesit("durum","")) $siparisler = $siparisler->where("durum",get("durum"));
                if(!getesit("urun","")) $siparisler = $siparisler->where("title",get("urun"));
                if(!getesit("siparis_id","")) $siparisler = $siparisler->where("type",get("siparis_id"));
                if(!getesit("urun2","")) $siparisler = $siparisler->where("json","like","%".get("urun2")."%");
                if(!getesit("date1","")) $siparisler = $siparisler->whereBetween("created_at",[get("date1"),get("date2")]);

            }
            $siparisler = $siparisler->orderBy("id","DESC")->simplePaginate(20);
            foreach($siparisler AS $s)  { 
                $j = j($s->json);
                $tamamlandi = j($s->tamamlandi);
             
             ?>
             <tr>
                 <td>
                     <?php if($s->cover!="") {
                          ?>
                           <a href="<?php echo e(picture2($s->cover,1024,0)); ?>"
										class="img-link img-link-zoom-in img-thumb img-lightbox" target="_blank"> <img
											src="<?php echo e(picture2($s->cover,128,0)); ?>" alt="" /> </a>
                         
                          <?php 
                     } ?>
                 </td>
                 <td><?php echo e($s->id); ?></td>
                 <td><?php echo e($s->type); ?></td>
                 <td><?php echo e($s->title); ?></td>
                 <td>
              
                     <form action="" id="form<?php echo e($s->id); ?>" method="post">
                         <?php echo csrf_field(); ?>
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td><?php echo e(e2("Ürün Adı")); ?></td>
                            <td><?php echo e(e2("Adet")); ?></td>
                            <td><?php echo e(e2("İşlem")); ?></td>
                        </tr>
                        <?php 
                        $k = 0;
                        try {
                            foreach($j['urun'] AS $urun)  { 
                            
                                if(isset($urunler[$urun]))  { 
                                 
                                 $bu_urun = $urunler[$urun];
                                 
                               ?>
                              <tr>
                                  <td><?php echo e($bu_urun->title); ?> <?php echo e($bu_urun->renk); ?></td>
                                  <td><?php echo e($j['qty'][$k]); ?></td>
                                  <td><input type="checkbox" name="<?php echo e($k); ?>" <?php if(@$tamamlandi[$k]=="on") echo "checked"; ?>   class="tamamlandi" data-id="<?php echo e($s->id); ?>" id=""></td>
                              </tr>  
                                 <?php } ?> 
                                 <?php $k++; } ?>
                            <?php 
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                       ?>
                           
                        
                    </table>
                    </form>
                    <?php if($s->json2!="") {
                         ?>
                          <a class="btn btn-primary" title="<?php echo e(e2("Bu siparişte değişiklikler yapıldı. Değişikliği görmek için tıklayınız")); ?>" data-toggle="collapse" href="#collapseExample<?php echo e($s->id); ?>" role="button" aria-expanded="false" aria-controls="collapseExample<?php echo e($s->id); ?>">
                            <i class="fa fa-history"></i>
                        </a>
                        <div class="collapse" id="collapseExample<?php echo e($s->id); ?>">
                            <div class="card card-body">
                                <?php $json2 = j($s->json2); 
                                
                                $json2 = j($json2['json']);
                                unset($json2['_token']);
                                ?>
                               <table class="table table-striped table-bordered">
                                    <tr class="table-warning">
                                        <th><?php echo e(e2("Ürün Adı")); ?></th>
                                        <th><?php echo e(e2("Adet")); ?></th>
                                    </tr>
                                    <?php 
                                        $k = 0;
                                        try {
                                            foreach($json2['urun'] AS $urun)  { 
                                            
                                                if(isset($urunler[$urun]))  { 
                                                
                                                $bu_urun = $urunler[$urun];
                                                
                                            ?>
                                            <tr class="table-warning">
                                                <td><?php echo e($bu_urun->title); ?> <?php echo e($bu_urun->renk); ?></td>
                                                <td><?php echo e($json2['qty'][$k]); ?></td>
                                            </tr>  
                                                <?php } ?> 
                                                <?php $k++; } ?>
                                            <?php 
                                        } catch (\Throwable $th) {
                                            //throw $th;
                                        }
                                    ?>
                                    
                                    
                                </table>
                                <?php if($json2['html']!=$s->html)  { 
                                  ?>
                                 <strong><?php echo e(e2("Değiştirilen Açıklama")); ?></strong> : <?php echo e($json2['html']); ?> 
                                 <?php } ?>
                            </div>
                        </div>
                         
                         <?php 
                    }  ?>
                 </td>
                 <td><?php echo e($s->qty); ?></td>
                 <td><?php echo e(date("d.m.Y H:i",strtotime($s->created_at))); ?></td>
                 <td><?php echo e($s->html); ?>

                  
                 </td>
                 <td>
                     <select name="durum" table="siparis_grubu" id="<?php echo e($s->id); ?>" class="form-control edit">
                         <option value="">Seçiniz</option>
                         <?php $durumlar = siparis_durumlari();
                         foreach($durumlar AS $d)  { 
                          
                          ?>
                          <option value="<?php echo e($d); ?>" <?php if($d==$s->durum) echo "selected"; ?>><?php echo e($d); ?></option> 
                          <?php } ?>
                     </select>
                 </td>
                 <td>
                     <a href="?siparis-duzenle=<?php echo e($s->id); ?>" class="btn btn-success"><i class="fa fa-edit"></i></a>
                     <a href="?siparis-sil=<?php echo e($s->id); ?>" teyit="<?php echo e(e2("Bu siparişi silmek istediğinizden emin misiniz?")); ?>" class="btn btn-danger"><i class="fa fa-times"></i></a>
                 </td>
             </tr> 
             <?php } ?>
        </table>
    </div>
    <?php $get = $_GET;
    unset($get['siparis-ekle']);
    unset($get['siparis-sil']);
    ?>
    <?php echo e($siparisler->appends($get)->links()); ?>

<?php echo e(_col()); ?><?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/admin/type/siparisler/siparis-listesi.blade.php ENDPATH**/ ?>