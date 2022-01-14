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
		  {{col("col-md-6","Ürün Bilgisi",1)}}
		  	<?php if($urun->cover!="") {
				   ?>
				 <img src="{{url('cache/small/'.$urun->cover)}}" alt="">  
				   <?php 
			  } ?>
				  <svg id="barcode" style="width:100%; "></svg>  <br>
				<script>
					JsBarcode("#barcode", "{{$urun->id}}", {
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
				  {{e2("Kat. SKU")}}
				<input type="text" name="kat_sku" value="{{$urun->kat_sku}}" table="contents"
						id="{{$urun->id}}" class="sku{{$urun->id}} form-control edit" />
				{{e2("SKU")}}
				<input type="text" name="sku" value="{{$urun->sku}}" table="contents"
						id="{{$urun->id}}" class="sku{{$urun->id}} form-control edit" />
				{{e2("Eşik Değer (Kritik Değer)")}}
				<input type="text" name="esik" value="{{$urun->esik}}" table="contents"
						id="{{$urun->id}}" class="esik{{$urun->id}} form-control edit" />
						<!--
				{{e2("Başlangıç Stok")}}
				<input type="text" name="stok" value="{{$urun->stok}}" table="contents"
						id="{{$urun->id}}" class="sku{{$urun->id}} form-control edit" />
				-->
				{{e2("Stok Durumu")}} <br>
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
								<div title="{{e2("Başlangıç Stok + Toplam Stok Girişi")}}" class="btn btn-success">{{$giris}}</div> 
									<div class="btn btn-default">-</div>
								<div title="{{e2("Toplam Stok Çıkışı")}}" class="btn btn-danger">{{$cikis}}</div>
								<div class="btn btn-default">=</div>
								<div title="{{e2("Kalan Stok:")}}" class="btn btn-info">{{$giris-$cikis}}</div>
								</div>
								{{$giris-$cikis}}
								<br>
				{{__("Ürün Adı")}}
				<input type="text" name="title" value="{{$urun->title}}" table="contents"
						id="{{$urun->id}}" class="sku{{$urun->id}} form-control edit" />
				{{__("Kategori Adı")}}
				<input type="text" name="title2" value="{{$urun->title2}}" table="contents"
						id="{{$urun->id}}" class="sku{{$urun->id}} form-control edit" />
				{{__("Varyasyon")}}
				<input type="text" name="renk" value="{{$urun->renk}}" table="contents"
						id="{{$urun->id}}" class="sku{{$urun->id}} form-control edit" />
				{{__("Miktar Türü")}}
				<input type="text" name="miktar_tur" value="{{$urun->miktar_tur}}" table="contents"
						id="{{$urun->id}}" class="miktar_tur{{$urun->id}} form-control edit" />
				{{__("Miktar Çarpanı (Adet Başına Düşen)")}}
				<input type="number" step="any" name="miktar_carpan" value="{{$urun->miktar_carpan}}" table="contents"
						id="{{$urun->id}}" class="miktar_carpan{{$urun->id}} form-control edit" />
				{{__("Envanter Grubu")}}
				<input type="text" name="grup" value="{{$urun->grup}}" table="contents"
						id="{{$urun->id}}" class="sku{{$urun->id}} form-control edit" />
				{{__("Ürün Alt Özellikleri")}}
				<textarea   class="form-control edit" name="alt_type"  table="contents"
										id="{{$urun->id}}" id="" cols="30" rows="2">{{$urun->alt_type}}</textarea>
					
		  {{_col()}}
		  {{col("col-md-6","Stok Girişleri",4)}} 
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
			<form action="?detay={{get("detay")}}&stok-girisi" method="post">
				{{csrf_field()}}
				<div class="input-group">
					<input type="number" name="qty" min="1" id="" class="form-control" placeholder="Miktar">
					<input type="text" name="lokasyon" placeholder="Lokasyon" id="" class="form-control">
					<button class="btn btn-primary"><i class="fa fa-plus"></i></button>
				</div>
			</form>
			<div class="table-responsive">
				<table class="table">
					<tr>
						<th>{{e2("Tarih")}}</th>
						<th>{{e2("Miktar")}}</th>
						<th>{{e2("Lokasyon")}}</th>
						<th>{{e2("Personel")}}</th>
						<th>{{e2("İşlem")}}</th>
					</tr>
					<?php $stok_girisleri = db("stoklar")->where("type",get("detay"))
					->orderBy("id","DESC")
					->get(); foreach($stok_girisleri AS $sg)  { 
					  ?>
 					<tr id="sg{{$sg->id}}">
					 	<td>{{date("d.m.Y H:i",strtotime($sg->created_at))}}</td>
 						<td>{{$sg->qty}}</td>
 						<td>{{$sg->lokasyon}}</td>
 						<td>{{username($sg->uid,$users)}}</td>
						 <td>
							 <a href="?stok-sil={{$sg->id}}&detay={{get("detay")}}" ajax="#sg{{$sg->id}}" teyit="{{e2("Bu stok kaydını silmek istediğinizden emin misiniz?")}}" class="btn btn-danger"><i class="fa fa-times"></i></a>
						 </td>
 					</tr> 
					 <?php } ?>
				</table>
			</div>		
		{{_col()}}
      <?php col("col-md-6","{$urun->title} Sipariş Detayları",2) ?>
 		 <div class="table-responsive">
 			 <table class="table">
 				 <tr>
 					 <th>{{e2("Tarih")}}</th>
 					 
 					 <th>{{e2("Miktar")}}</th>
 					 <th>{{e2("Sevk Edilen")}}</th>
 					 <th>{{e2("Kalan")}}</th>
 					 <th>{{e2("Personel")}}</th>
 				 </tr>
 				 <?php foreach($siparisler AS $s)  { 
 				
 				  ?>
  				 <tr>
  					 <td>{{date("d.m.Y H:i",strtotime($s->created_at))}}</td>
  				
  					 <td>{{$s->qty}}</td>
  					 <td>
 						<?php 
 						//  print2($stok_cikis_sayim);
 						$stok_cikisi = 0;
 						if(isset($stok_cikis_sayim[$s->id])) {
 							$stok_cikisi = $stok_cikis_sayim[$s->id];
 						} 
 						?>
 						{{$stok_cikisi}}
 					</td>
 					<td>
 						{{$s->qty-$stok_cikisi}}
 					</td>
  					 <td>{{date("d.m.Y",strtotime($s->date))}}</td>
					   <td>{{username($s->uid,$users)}}</td>
  				 </tr> 
 				  <?php } ?>
 			 </table>
 		 </div>
 			
  		<?php _col(); ?> 
		
  		<?php col("col-md-6","{$urun->title} Stok Çıkışları",3) ?>
 			<div class="table-responsive">
                 <table class="table">
                     <tr>
                         <th>{{e2("Tarih")}}</th>
                         <th>{{e2("Barkod")}}</th>
                         <th>{{e2("Müşteri")}}</th>
                         <th>{{e2("Miktar")}}</th>
                         <th>{{e2("Personel")}}</th>
                     </tr>
                     <?php foreach($stok_cikislari AS $s)  { 
                         $stok = j($s->stok);
                         $siparis = j($s->siparis);
                         $musteri = $musteriler[$s->musteri_id];
                        
                      ?>
                      <tr>
                          <td>{{date("d.m.Y H:i",strtotime($s->created_at))}}</td>
                          <td>
                              <a href="?ajax=print-stok&id={{$stok['slug']}}&noprint" title="{{$stok['slug']}} Barkoduna Ait Bilgiler" class="ajax_modal">{{$stok['slug']}}</a>
                          </td>
                          <td>{{$musteri->title}} {{$musteri->title2}}</td>
                          <td>{{$s->qty}}</td>
						  <td>{{username($s->uid,$users)}}</td>
                      </tr> 
                      <?php } ?>
                 </table>
             </div>
  		<?php _col(); ?> 
 
      </div>
       
	 <?php } ?>
     
     <?php 
} ?>