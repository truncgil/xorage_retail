<?php 
$users = usersArray();
if(getisset("ekle")) {
	ekle([
		'slug' => rand(),
		'type' => "Ürünler",
		"kid" => "main"

	],"contents");
	
} 
$urunler = db("contents")->where("type","Ürünler");
if(getisset("urunler-keywords-guncelle")) {
	
	$urunler2 = $urunler->get();
	foreach($urunler2 AS $u2) {
		db("contents")
		->where("id",$u2->id)
		->update([
			"slug" => str_slug("{$u2->title} {$u2->title2} {$u2->renk} {$u2->grup}")
		]);
	}
}

$m = 10;
if(oturumisset("m")) {
	$m = oturum("m");
}
if(getisset("m")) {
	$m = get("m");
	$_SESSION['m'] = $m;
}

if(!getesit("q","")) {
	$qs = explode(" ",get("q"));
	foreach($qs AS $q) {
		$urunler = $urunler->where(function($query) use($q) {
			$slug = str_slug($q,"");
			$query = $query->orWhere("id",$q);	
			$query = $query->orWhere("title","like","%$q%");	
			$query = $query->orWhere("title2","like","%$q%");	
			$query = $query->orWhere("sku","like","%$q%");	
			$query = $query->orWhere("kat_sku","like","%$q%");	
			$query = $query->orWhere("renk","like","%$q%");	
			$query = $query->orWhere("grup","like","%$q%");	
			$query = $query->orWhere("title","like","%$slug%");	
			$query = $query->orWhere("slug","like","%$slug%");	
			$query = $query->orWhere("title2","like","%$slug%");	
			$query = $query->orWhere("grup","like","%$slug%");	
			$query = $query->orWhere("renk","like","%$slug%");	
		});
	}
	
}
if(!getesit("title2","")) {
	$urunler = $urunler->where("title2",get("title2"));
}
if(!getesit("grup","")) {
	$urunler = $urunler->where("grup",get("grup"));
}
if(!getesit("renk","")) {
	$urunler = $urunler->where("renk",get("renk"));
}

$urunler = $urunler
->orderBy("id","DESC")
->simplePaginate($m);
$stok_giris_sayim = stok_giris_sayim(); 
$stok_cikis_sayim = stok_cikis_sayim(); 
?>
<div class="content">
	@include("admin.type.urunler.urun-detay")
<?php 

	$tum_urunler = contents_to_array("Ürünler");
	$kategoriler = array();
	$varyasyon = array();
	$grup = array();
	foreach($tum_urunler AS $urun) {
		if($urun->title2!="") {
			if(!in_array($urun->title2,$kategoriler)) {
				array_push($kategoriler,$urun->title2);
			}
		}
		if($urun->renk!="") {
			if(!in_array($urun->renk,$varyasyon)) {
				array_push($varyasyon,$urun->renk);
			}
		}
		if($urun->grup!="") {
			if(!in_array($urun->grup,$grup)) {
				array_push($grup,$urun->grup);
			}
		}
		
	}
?>
	{{col("col-md-12","Filtrele")}}
		<form action="" method="get">
			<div class="row">
				
					<div class="col-md-4">
						{{e2("Kategori Adı")}} : 
						<select name="title2" id="" class="form-control select2">
							<option value="">{{e2("Tümü")}}</option>
							<?php foreach($kategoriler AS $k)  { 
							?>
							<option value="{{$k}}" <?php if(getesit("title2",$k)) echo "selected"; ?>>{{$k}}</option> 
							<?php } ?>
						</select>
					</div>
					<div class="col-md-4">
						{{e2("Varyasyon / Renk")}} : 
						<select name="renk" id="" class="form-control select2">
							<option value="">{{e2("Tümü")}}</option>
							<?php foreach($varyasyon AS $k)  { 
							?>
							<option value="{{$k}}" <?php if(getesit("renk",$k)) echo "selected"; ?>>{{$k}}</option> 
							<?php } ?>
						</select>
					</div>
					<div class="col-md-4">
						{{e2("Envanter Grubu")}} : 
						<select name="grup" id="" class="form-control select2">
							<option value="">{{e2("Tümü")}}</option>
							<?php foreach($grup AS $k)  { 
							?>
							<option value="{{$k}}" <?php if(getesit("gruo",$k)) echo "selected"; ?>>{{$k}}</option> 
							<?php } ?>
						</select>
					</div>
					<div class="col-md-12 mt-3">
						<div class="text-center">
							<button class="btn btn-primary">{{e2("Filtrele")}}</button>
						</div>
					</div>
			</div>
		</form>
	{{_col()}}
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title"><i class="fa fa-{{$c->icon}}"></i> {{e2($c->title)}} {{__('Listesi')}}</h3>
			<div class="block-options">
				<div class="block-options-item"> 
				<button onclick="var bu = $(this); bu.html('İşlem yapılıyor...');$.get('?urunler-keywords-guncelle',function(){
					bu.html('İşlem tamamlandı.');
				});" title="{{e2("Eğer bir ürün aramada zorluk yaşıyorsanız bu işlemi yapınız")}}" target="_blank" class="btn btn-warning"><i class="fa fa-search"></i> {{e2("Aramayı İyileştir")}}</button>	
				<a href="?ajax=print-barkodlar" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> {{e2("Tüm Ürün Barkodlarını Yazdır")}}</a>	
				<a
						href="{{ url('admin-ajax/content-type-blank-delete?type='. $c->title) }}"
						teyit="{{__('Adı boş olan tüm ürünler silinecektir onaylıyor musunuz?')}}" title="{{_('Boş Olan  Ürünleri Sil')}}"
						class="btn btn-danger d-none"><i class="fa fa-times"></i> </a> <a
						href="?ekle" class="btn btn-success"
						title="Yeni Ürün Ekle"><i class="fa fa-plus"></i> {{e2("Yeni Ürün Ekle")}}</a> </div>
			</div>
		</div>
		<div class="block-content">
			<div class="js-gallery "> @include("admin.inc.table-search") <div class="table-responsive">
				<script>
					$(function(){
						$("[data-action='sidebar_toggle']").trigger("click");
					});
				</script>
					<table class="table table-striped table-hover table-bordered table-vcenter" id="excel">
						<thead>
							<tr>
								<th>{{e2("Barkod")}}</th>
								<th class="text-center" style="width: 50px;">{{__("Görsel")}}</th>
								<th class="d-none">{{e2("Kat. SKU")}}</th>
								<th>{{e2("SKU")}}</th>
								<th class="d-none">{{e2("Başlangıç Stok")}}</th>
								<th>{{e2("Stok Durumu")}}</th>
								
								<th>{{__("Ürün Adı")}}</th>
								<th>{{__("Kategori Adı")}}</th>
								<th>{{__("Varyasyon")}}</th>
								<th>{{__("Envanter Grubu")}}</th>
								<th class="d-none">{{__("Ürün Alt Özellikleri")}}</th>
								<th class="d-none">{{__("Personel")}}</th>
								
								<th class="text-center" style="width: 100px;">{{__("İşlemler")}}</th>
							</tr>
						</thead>
						<tbody> @foreach($urunler AS $a) <tr class="">
							<th>{{$a->id}}</th>
							<th class="text-center cover" scope="row"> @if($a->cover!='') <a
										href="{{url('cache/large/'.$a->cover)}}"
										class="img-link img-link-zoom-in img-thumb img-lightbox" target="_blank"> <img
											src="{{url('cache/small/'.$a->cover)}}" alt="" /> </a>
									<hr /> @endif <div class="btn-group"> <button type="button"
											class="btn  btn-secondary btn-sm"
											onclick="$('#c{{$a->id}}').trigger('click');"
											title="{{__('Resim Yükle')}}"><i class="fa fa-upload"></i></button>
										@if($a->cover!='') <a
											teyit="{{__('Resmi kaldırmak istediğinizden emin misiniz')}}"
											title="{{__('Resmi kaldır')}}"
											href="{{url('admin-ajax/cover-delete?id='.$a->id)}}"
											class="btn btn-secondary btn-sm "><i class="fa fa-times"></i></a> <a
											title="{{__('Resmi indir')}}" href="{{url('cache/download/'.$a->cover)}}"
											class="btn btn-secondary btn-sm"><i class="fa fa-download"></i></a> @endif
									</div>
									<form action="{{url('admin-ajax/cover-upload')}}" id="f{{$a->id}}"
										class="hidden-upload" enctype="multipart/form-data" method="post"> <input
											type="file" name="cover" id="c{{$a->id}}"
											onchange="$('#f{{$a->id}}').submit();" required /> <input type="hidden"
											name="id" value="{{$a->id}}" /> <input type="hidden" name="slug"
											value="{{$a->slug}}" /> {{csrf_field()}} </form>
								</th>
								
								
								<td  width="30">
									<div class="d-none">{{$a->sku}}</div>
                                    <input type="text" name="sku" value="{{$a->sku}}" table="contents"
										id="{{$a->id}}" class="sku{{$a->id}} form-control edit" />
								</td>
								<td class="d-none">
									<div class="d-none">{{$a->kat_sku}}</div>
                                    <input type="text" name="kat_sku" value="{{$a->kat_sku}}" table="contents"
										id="{{$a->id}}" class="kat_sku{{$a->id}} form-control edit" />
								</td>
								<td width="50" class="d-none">
									<div class="d-none">{{$a->stok}}</div>
                                    <input type="text" title="{{e2("Başlangıç Stok")}}" name="stok" value="{{$a->stok}}" table="contents"
										id="{{$a->id}}" class="stok{{$a->id}} form-control edit" />
									
								</td>
								<td>
									<?php 
									$giris = 0;
									$cikis = 0;
									if(isset($stok_giris_sayim[$a->id])) {
										$giris = $stok_giris_sayim[$a->id];
									}
										 ?>
										 
										
										 
									<?php if(isset($stok_cikis_sayim[$a->id])) {
										$cikis = $stok_cikis_sayim[$a->id];
									}
										 ?>
										 {{$giris-$cikis}}
										 <div class="btn-group d-none">
										 	<div title="{{e2("Başlangıç Stok + Toplam Stok Girişi")}}" class="btn btn-success">{{$giris}}</div> 
											 <div class="btn btn-default">-</div>
											<div title="{{e2("Toplam Stok Çıkışı")}}" class="btn btn-danger">{{$cikis}}</div>
											<div class="btn btn-default">=</div>
											<div title="{{e2("Kalan Stok:")}}" class="btn btn-info">{{$giris-$cikis}}</div>
										 </div>
										 
										 
										 
								</td>
								<td width="500">
									<div class="d-none">{{$a->title}}</div>
                                    <input type="text" name="title"  value="{{$a->title}}" table="contents"
										id="{{$a->id}}" class="title{{$a->id}} form-control edit" />
								</td>
								<td width="50">
									<div class="d-none">{{$a->title2}}</div>
                                    <input type="text" name="title2" value="{{$a->title2}}" table="contents"
										id="{{$a->id}}" class="title2{{$a->id}} form-control edit" />
								</td>
								<td  width="50">
									<div class="d-none">{{$a->renk}}</div>
                                    <input type="text" name="renk" value="{{$a->renk}}" table="contents"
										id="{{$a->id}}" class="renk{{$a->id}} form-control edit" />
								</td>
								<td  width="50">
									<div class="d-none">{{$a->grup}}</div>
                                    <input type="text" name="grup" value="{{$a->grup}}" table="contents"
										id="{{$a->id}}" class="grup{{$a->id}} form-control edit" />
								</td>
								<td class="d-none">
									<textarea   class="form-control edit" name="alt_type"  table="contents"
										id="{{$a->id}}" id="" cols="30" rows="2">{{$a->alt_type}}</textarea>
                                   
								 </td>
								
								 <td class="d-none">
									{{username($a->uid,$users)}}
								</td>
								<td class="text-center">
									<div class="btn-group"> 
											<!--
											<a href="{{ url('admin/contents/'. $a->slug) }}"
											class="btn btn-secondary js-tooltip-enabled" data-toggle="tooltip" title=""
											data-original-title="Edit"> <i class="fa fa-edit"></i> </a>
											-->
											<a
											href="{{ url('admin/contents/'. $a->slug .'/delete') }}"
											teyit="{{$a->title}} {{__('içeriğini silmek istediğinizden emin misiniz?')}}"
											title="{{$a->title}} {{__('Silinecek!')}}"
											class=" btn  btn-danger js-tooltip-enabled" data-toggle="tooltip"
											title="" data-original-title="Delete"> <i class="fa fa-times"></i> </a>
											<a href="?detay={{$a->id}}" title="{{e2("Ürün Detayları")}}" class="btn btn-primary">
												<i class="fa fa-list"></i>
											</a>
											<a href="?ajax=print-urun-barkodu&id={{$a->id}}" title="{{e2("Bu ürün için çoklu barkod yazdır")}}" target="_blank" class="btn btn-success"><i class="fa fa-print"></i></a>
									</div>
								</td>
								
							</tr> @endforeach </tbody>
					</table> {{$urunler->appends(request()->except(['page','_token']))->links() }}
				</div>
			</div>
		</div>
	</div>
</div>