<?php 
 $users = usersArray();
if(getisset("detay")) {
    $urun = c(get("detay"));
	if($urun)  { 
	 
     $musteriler = contents_to_array("Müşteriler");
     $siparisler = db("siparisler")->where("type",get("detay"))
     ->orderBy("id","DESC")
     ->get();
     $stok_cikislari = db("stok_cikislari")
     ->where("stok->type",get("detay"))
     ->orderBy("id","DESC")
     ->get();
     $stok_cikis_sayim = stok_cikis_sayim();
      ?> 
      <div class="row">
		  <?php echo e(col("col-md-6","Ürün Bilgisi",1)); ?>

		  	<?php if($urun->cover!="") {
				   ?>
				 <img src="<?php echo e(url('cache/small/'.$urun->cover)); ?>" alt="">  
				   <?php 
			  } ?>
				  <svg id="barcode" style="width:100%; "></svg>  <br>
				<script>
					JsBarcode("#barcode", "<?php echo e($urun->id); ?>", {
						format: "CODE128",
						height:60,
						
						displayValue: true
					});
				
				</script>
				<style>
					#barcode {
						width:100%;
						display:block;
						margin: 0 auto
					}
				</style>
				  <?php echo e(e2("Kat. SKU")); ?>

				<input type="text" name="kat_sku" value="<?php echo e($urun->kat_sku); ?>" table="contents"
						id="<?php echo e($urun->id); ?>" class="sku<?php echo e($urun->id); ?> form-control edit" />
				<?php echo e(e2("SKU")); ?>

				<input type="text" name="sku" value="<?php echo e($urun->sku); ?>" table="contents"
						id="<?php echo e($urun->id); ?>" class="sku<?php echo e($urun->id); ?> form-control edit" />
				<?php echo e(e2("Eşik Değer (Kritik Değer)")); ?>

				<input type="text" name="esik" value="<?php echo e($urun->esik); ?>" table="contents"
						id="<?php echo e($urun->id); ?>" class="esik<?php echo e($urun->id); ?> form-control edit" />
						<!--
				<?php echo e(e2("Başlangıç Stok")); ?>

				<input type="text" name="stok" value="<?php echo e($urun->stok); ?>" table="contents"
						id="<?php echo e($urun->id); ?>" class="sku<?php echo e($urun->id); ?> form-control edit" />
				-->
				<?php echo e(e2("Stok Durumu")); ?> <br>
						<?php 
						$giris = 0;
						$cikis = 0;
						if(isset($stok_giris_sayim[$urun->id])) {
							$giris = $urun->stok+$stok_giris_sayim[$urun->id];
						}
								?>
								
							
								
						<?php if(isset($stok_cikis_sayim[$urun->id])) {
							$cikis = $stok_cikis_sayim[$urun->id];
						}
								?>
								<div class="btn-group d-none">
								<div title="<?php echo e(e2("Başlangıç Stok + Toplam Stok Girişi")); ?>" class="btn btn-success"><?php echo e($giris); ?></div> 
									<div class="btn btn-default">-</div>
								<div title="<?php echo e(e2("Toplam Stok Çıkışı")); ?>" class="btn btn-danger"><?php echo e($cikis); ?></div>
								<div class="btn btn-default">=</div>
								<div title="<?php echo e(e2("Kalan Stok:")); ?>" class="btn btn-info"><?php echo e($giris-$cikis); ?></div>
								</div>
								<?php echo e($giris-$cikis); ?>

								<br>
				<?php echo e(__("Ürün Adı")); ?>

				<input type="text" name="title" value="<?php echo e($urun->title); ?>" table="contents"
						id="<?php echo e($urun->id); ?>" class="sku<?php echo e($urun->id); ?> form-control edit" />
				<?php echo e(__("Kategori Adı")); ?>

				<input type="text" name="title2" value="<?php echo e($urun->title2); ?>" table="contents"
						id="<?php echo e($urun->id); ?>" class="sku<?php echo e($urun->id); ?> form-control edit" />
				<?php echo e(__("Varyasyon")); ?>

				<input type="text" name="renk" value="<?php echo e($urun->renk); ?>" table="contents"
						id="<?php echo e($urun->id); ?>" class="sku<?php echo e($urun->id); ?> form-control edit" />
				<?php echo e(__("Miktar Türü")); ?>

				<input type="text" name="miktar_tur" value="<?php echo e($urun->miktar_tur); ?>" table="contents"
						id="<?php echo e($urun->id); ?>" class="miktar_tur<?php echo e($urun->id); ?> form-control edit" />
				<?php echo e(__("Miktar Çarpanı (Adet Başına Düşen)")); ?>

				<input type="number" step="any" name="miktar_carpan" value="<?php echo e($urun->miktar_carpan); ?>" table="contents"
						id="<?php echo e($urun->id); ?>" class="miktar_carpan<?php echo e($urun->id); ?> form-control edit" />
				<?php echo e(__("Envanter Grubu")); ?>

				<input type="text" name="grup" value="<?php echo e($urun->grup); ?>" table="contents"
						id="<?php echo e($urun->id); ?>" class="sku<?php echo e($urun->id); ?> form-control edit" />
				<?php echo e(__("Ürün Alt Özellikleri")); ?>

				<textarea   class="form-control edit" name="alt_type"  table="contents"
										id="<?php echo e($urun->id); ?>" id="" cols="30" rows="2"><?php echo e($urun->alt_type); ?></textarea>
					
		  <?php echo e(_col()); ?>

		  <?php echo e(col("col-md-6","Stok Girişleri",4)); ?> 
		<?php 
		if(getisset("stok-sil")) {
			db("stoklar")
			->where("id",get("stok-sil"))
			->delete();
		}
		if(getisset("stok-girisi")) {
			ekle([
				'qty' => post("qty"),
				"lokasyon" => post("lokasyon"),
				"type" => get("detay")
			],"stoklar");
		} ?>
			<form action="?detay=<?php echo e(get("detay")); ?>&stok-girisi" method="post">
				<?php echo e(csrf_field()); ?>

				<div class="input-group">
					<input type="number" name="qty" min="1" id="" class="form-control" placeholder="Miktar">
					<input type="text" name="lokasyon" placeholder="Lokasyon" id="" class="form-control">
					<button class="btn btn-primary"><i class="fa fa-plus"></i></button>
				</div>
			</form>
			<div class="table-responsive">
				<table class="table">
					<tr>
						<th><?php echo e(e2("Tarih")); ?></th>
						<th><?php echo e(e2("Miktar")); ?></th>
						<th><?php echo e(e2("Lokasyon")); ?></th>
						<th><?php echo e(e2("Personel")); ?></th>
						<th><?php echo e(e2("İşlem")); ?></th>
					</tr>
					<?php $stok_girisleri = db("stoklar")->where("type",get("detay"))
					->orderBy("id","DESC")
					->get(); foreach($stok_girisleri AS $sg)  { 
					  ?>
 					<tr id="sg<?php echo e($sg->id); ?>">
					 	<td><?php echo e(date("d.m.Y H:i",strtotime($sg->created_at))); ?></td>
 						<td><?php echo e($sg->qty); ?></td>
 						<td><?php echo e($sg->lokasyon); ?></td>
 						<td><?php echo e(username($sg->uid,$users)); ?></td>
						 <td>
							 <a href="?stok-sil=<?php echo e($sg->id); ?>&detay=<?php echo e(get("detay")); ?>" ajax="#sg<?php echo e($sg->id); ?>" teyit="<?php echo e(e2("Bu stok kaydını silmek istediğinizden emin misiniz?")); ?>" class="btn btn-danger"><i class="fa fa-times"></i></a>
						 </td>
 					</tr> 
					 <?php } ?>
				</table>
			</div>		
		<?php echo e(_col()); ?>

      <?php col("col-md-6","{$urun->title} Sipariş Detayları",2) ?>
 		 <div class="table-responsive">
 			 <table class="table">
 				 <tr>
 					 <th><?php echo e(e2("Tarih")); ?></th>
 					 
 					 <th><?php echo e(e2("Miktar")); ?></th>
 					 <th><?php echo e(e2("Sevk Edilen")); ?></th>
 					 <th><?php echo e(e2("Kalan")); ?></th>
 					 <th><?php echo e(e2("Personel")); ?></th>
 				 </tr>
 				 <?php foreach($siparisler AS $s)  { 
 				
 				  ?>
  				 <tr>
  					 <td><?php echo e(date("d.m.Y H:i",strtotime($s->created_at))); ?></td>
  				
  					 <td><?php echo e($s->qty); ?></td>
  					 <td>
 						<?php 
 						//  print2($stok_cikis_sayim);
 						$stok_cikisi = 0;
 						if(isset($stok_cikis_sayim[$s->id])) {
 							$stok_cikisi = $stok_cikis_sayim[$s->id];
 						} 
 						?>
 						<?php echo e($stok_cikisi); ?>

 					</td>
 					<td>
 						<?php echo e($s->qty-$stok_cikisi); ?>

 					</td>
  					 <td><?php echo e(date("d.m.Y",strtotime($s->date))); ?></td>
					   <td><?php echo e(username($s->uid,$users)); ?></td>
  				 </tr> 
 				  <?php } ?>
 			 </table>
 		 </div>
 			
  		<?php _col(); ?> 
		
  		<?php col("col-md-6","{$urun->title} Stok Çıkışları",3) ?>
 			<div class="table-responsive">
                 <table class="table">
                     <tr>
                         <th><?php echo e(e2("Tarih")); ?></th>
                         <th><?php echo e(e2("Barkod")); ?></th>
                         <th><?php echo e(e2("Müşteri")); ?></th>
                         <th><?php echo e(e2("Miktar")); ?></th>
                         <th><?php echo e(e2("Personel")); ?></th>
                     </tr>
                     <?php foreach($stok_cikislari AS $s)  { 
                         $stok = j($s->stok);
                         $siparis = j($s->siparis);
                         $musteri = $musteriler[$s->musteri_id];
                        
                      ?>
                      <tr>
                          <td><?php echo e(date("d.m.Y H:i",strtotime($s->created_at))); ?></td>
                          <td>
                              <a href="?ajax=print-stok&id=<?php echo e($stok['slug']); ?>&noprint" title="<?php echo e($stok['slug']); ?> Barkoduna Ait Bilgiler" class="ajax_modal"><?php echo e($stok['slug']); ?></a>
                          </td>
                          <td><?php echo e($musteri->title); ?> <?php echo e($musteri->title2); ?></td>
                          <td><?php echo e($s->qty); ?></td>
						  <td><?php echo e(username($s->uid,$users)); ?></td>
                      </tr> 
                      <?php } ?>
                 </table>
             </div>
  		<?php _col(); ?> 
 
      </div>
       
	 <?php } ?>
     
     <?php 
} ?><?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/admin/type/urunler/urun-detay.blade.php ENDPATH**/ ?>