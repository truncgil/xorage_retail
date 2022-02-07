<?php 
			$urunler = contents_to_array("Ürünler");
			$giris_stok = stok_giris_sayim();
			$cikis_stok = stok_cikis_sayim();

			?>
			<script>
			$(document).ready(function(){
			$("#ara").on("keyup", function() {
				var value = $(this).val().toLowerCase();
				$("#excel tbody tr").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				});
			});
			$(".filtre-btn").on("click",function(){
				var value = $(this).attr("data-id")+"-"+$(".filter-select").val();
				$("#ara").val(value);
				/*
				$("#tablo tbody tr").filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				});
				*/
				$("#ara").trigger("keyup");

			});
			$(".filter-select").on("change",function(){
				var value = $(this).val();
				console.log(value);
				$("#excel tbody tr").filter(function() {
					$(this).toggle($(this).text().indexOf(value) > -1)
				});

			});
			});
			</script>
			<?php $kategoriler = [];
			foreach($urunler AS $u) {
				if(!in_array($u->title2,$kategoriler)) {
					array_push($kategoriler,$u->title2);
				}
				
			}
			?>
			<div class="row">
				<div class="col-6">
					<input type="text" name="" placeholder="{{e2("Ara...")}}" id="ara" class="form-control">
				</div>
				<div class="col-6">
					<select name="" id="" class="form-control select2 filter-select">
						<option value="">{{e2("Tüm Kategoriler")}}</option>
						<?php foreach($kategoriler AS $k)  { 
						?>
							<option value="{{$k}}">{{$k}}</option> 
						<?php } ?>
					</select>
				</div>
			</div>
            <div class="table-responsive">
				<div class="btn btn-warning filtre-btn" data-id="warning">{{e2("Kritik Stok")}}</div>
				<div class="btn btn-primary filtre-btn" data-id="primary">{{e2("Yeterli Stok")}}</div>
				<div class="btn btn-danger filtre-btn" data-id="danger">{{e2("Eksi Stok")}}</div>
				<div class="btn btn-success filtre-btn" data-id="">{{e2("Tümü")}}</div>
                <table class="table table-bordered table-striped table-hover" id="excel">
					<thead>
                    <tr>
                        <th>{{e2("ÜRÜN ADI")}}</th>
                        <th>{{e2("GİRİŞ")}}</th>
                        <th>{{e2("ÇIKIŞ")}}</th>
                        <th>{{e2("KALAN STOK")}}</th>
                    </tr>
					</thead>
					<tbody>
                    <?php  foreach($urunler AS $u) { 
                       		$giris = 0;
						   $cikis = 0;
						   
						   if(isset($giris_stok[$u->id])) $giris = $giris_stok[$u->id];
						   if(isset($cikis_stok[$u->id])) $cikis = $cikis_stok[$u->id];
						   if($u->miktar_tur!="Adet") {
								$giris = $giris * $u->miktar_carpan;
								$cikis = $cikis * $u->miktar_carpan;
							}
						   $kalan = $giris - $cikis;
						   if($kalan<0) {
							   $durum = "danger";
						   } elseif($kalan<$u->esik) {
							   $durum = "warning";
						   } else {
							   $durum = "primary";
						   }
						   
						   

                            
                        ?>
                        <tr class="table-{{$durum}}">
                            <td>{{$u->id}} {{$u->title}} {{$u->renk}}
							<div class="d-none">{{str_slug($u->title)}} {{str_slug($u->title2)}} {{str_slug($u->grup)}} {{str_slug($u->renk)}} {{($u->title2)}} {{$u->grup}}</div>
							
							<div class="d-none">{{$durum}}-{{$u->title2}}</div>

							</td>
                            <td>{{nf($giris,$u->miktar_tur)}}</td>
                            <td>{{nf($cikis,$u->miktar_tur)}}</td>
                            <td>{{nf($kalan,$u->miktar_tur)}} </td>
                        </tr>
                <?php } ?>
				</tbody>
                </table>
            </div>
		
            