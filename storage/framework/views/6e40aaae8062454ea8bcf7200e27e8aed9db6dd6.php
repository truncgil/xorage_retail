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
$m = 10;
if(oturumisset("m")) {
	$m = oturum("m");
}
if(getisset("m")) {
	$m = get("m");
	$_SESSION['m'] = $m;
}

if(!getesit("q","")) {
	$q = get("q");
	$urunler = $urunler->where(function($query) use($q) {
		$query = $query->orWhere("id",$q);	
		$query = $query->orWhere("title","like","%$q%");	
		$query = $query->orWhere("sku","like","%$q%");	
		$query = $query->orWhere("kat_sku","like","%$q%");	
		$query = $query->orWhere("renk","like","%$q%");	
		$query = $query->orWhere("grup","like","%$q%");	
	});
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
	<?php echo $__env->make("admin.type.urunler.urun-detay", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
	<?php echo e(col("col-md-12","Filtrele")); ?>

		<form action="" method="get">
			<div class="row">
				
					<div class="col-md-4">
						<?php echo e(e2("Kategori Adı")); ?> : 
						<select name="title2" id="" class="form-control select2">
							<option value=""><?php echo e(e2("Tümü")); ?></option>
							<?php foreach($kategoriler AS $k)  { 
							?>
							<option value="<?php echo e($k); ?>" <?php if(getesit("title2",$k)) echo "selected"; ?>><?php echo e($k); ?></option> 
							<?php } ?>
						</select>
					</div>
					<div class="col-md-4">
						<?php echo e(e2("Varyasyon / Renk")); ?> : 
						<select name="renk" id="" class="form-control select2">
							<option value=""><?php echo e(e2("Tümü")); ?></option>
							<?php foreach($varyasyon AS $k)  { 
							?>
							<option value="<?php echo e($k); ?>" <?php if(getesit("renk",$k)) echo "selected"; ?>><?php echo e($k); ?></option> 
							<?php } ?>
						</select>
					</div>
					<div class="col-md-4">
						<?php echo e(e2("Envanter Grubu")); ?> : 
						<select name="grup" id="" class="form-control select2">
							<option value=""><?php echo e(e2("Tümü")); ?></option>
							<?php foreach($grup AS $k)  { 
							?>
							<option value="<?php echo e($k); ?>" <?php if(getesit("gruo",$k)) echo "selected"; ?>><?php echo e($k); ?></option> 
							<?php } ?>
						</select>
					</div>
					<div class="col-md-12 mt-3">
						<div class="text-center">
							<button class="btn btn-primary"><?php echo e(e2("Filtrele")); ?></button>
						</div>
					</div>
			</div>
		</form>
	<?php echo e(_col()); ?>

	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title"><i class="fa fa-<?php echo e($c->icon); ?>"></i> <?php echo e(e2($c->title)); ?> <?php echo e(__('Listesi')); ?></h3>
			<div class="block-options">
				<div class="block-options-item"> 
				<a href="?ajax=print-barkodlar" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> <?php echo e(e2("Tüm Ürün Barkodlarını Yazdır")); ?></a>	
				<a
						href="<?php echo e(url('admin-ajax/content-type-blank-delete?type='. $c->title)); ?>"
						teyit="<?php echo e(__('Adı boş olan tüm ürünler silinecektir onaylıyor musunuz?')); ?>" title="<?php echo e(_('Boş Olan  Ürünleri Sil')); ?>"
						class="btn btn-danger d-none"><i class="fa fa-times"></i> </a> <a
						href="?ekle" class="btn btn-success"
						title="Yeni Ürün Ekle"><i class="fa fa-plus"></i> <?php echo e(e2("Yeni Ürün Ekle")); ?></a> </div>
			</div>
		</div>
		<div class="block-content">
			<div class="js-gallery "> <?php echo $__env->make("admin.inc.table-search", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> <div class="table-responsive">
				<script>
					$(function(){
						$("[data-action='sidebar_toggle']").trigger("click");
					});
				</script>
					<table class="table table-striped table-hover table-bordered table-vcenter" id="excel">
						<thead>
							<tr>
								<th><?php echo e(e2("Barkod")); ?></th>
								<th class="text-center" style="width: 50px;"><?php echo e(__("Görsel")); ?></th>
								<th class="d-none"><?php echo e(e2("Kat. SKU")); ?></th>
								<th><?php echo e(e2("SKU")); ?></th>
								<th class="d-none"><?php echo e(e2("Başlangıç Stok")); ?></th>
								<th><?php echo e(e2("Stok Durumu")); ?></th>
								
								<th><?php echo e(__("Ürün Adı")); ?></th>
								<th><?php echo e(__("Kategori Adı")); ?></th>
								<th><?php echo e(__("Varyasyon")); ?></th>
								<th><?php echo e(__("Envanter Grubu")); ?></th>
								<th class="d-none"><?php echo e(__("Ürün Alt Özellikleri")); ?></th>
								<th class="d-none"><?php echo e(__("Personel")); ?></th>
								
								<th class="text-center" style="width: 100px;"><?php echo e(__("İşlemler")); ?></th>
							</tr>
						</thead>
						<tbody> <?php $__currentLoopData = $urunler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <tr class="">
							<th><?php echo e($a->id); ?></th>
							<th class="text-center cover" scope="row"> <?php if($a->cover!=''): ?> <a
										href="<?php echo e(url('cache/large/'.$a->cover)); ?>"
										class="img-link img-link-zoom-in img-thumb img-lightbox" target="_blank"> <img
											src="<?php echo e(url('cache/small/'.$a->cover)); ?>" alt="" /> </a>
									<hr /> <?php endif; ?> <div class="btn-group"> <button type="button"
											class="btn  btn-secondary btn-sm"
											onclick="$('#c<?php echo e($a->id); ?>').trigger('click');"
											title="<?php echo e(__('Resim Yükle')); ?>"><i class="fa fa-upload"></i></button>
										<?php if($a->cover!=''): ?> <a
											teyit="<?php echo e(__('Resmi kaldırmak istediğinizden emin misiniz')); ?>"
											title="<?php echo e(__('Resmi kaldır')); ?>"
											href="<?php echo e(url('admin-ajax/cover-delete?id='.$a->id)); ?>"
											class="btn btn-secondary btn-sm "><i class="fa fa-times"></i></a> <a
											title="<?php echo e(__('Resmi indir')); ?>" href="<?php echo e(url('cache/download/'.$a->cover)); ?>"
											class="btn btn-secondary btn-sm"><i class="fa fa-download"></i></a> <?php endif; ?>
									</div>
									<form action="<?php echo e(url('admin-ajax/cover-upload')); ?>" id="f<?php echo e($a->id); ?>"
										class="hidden-upload" enctype="multipart/form-data" method="post"> <input
											type="file" name="cover" id="c<?php echo e($a->id); ?>"
											onchange="$('#f<?php echo e($a->id); ?>').submit();" required /> <input type="hidden"
											name="id" value="<?php echo e($a->id); ?>" /> <input type="hidden" name="slug"
											value="<?php echo e($a->slug); ?>" /> <?php echo e(csrf_field()); ?> </form>
								</th>
								
								
								<td  width="30">
									<div class="d-none"><?php echo e($a->sku); ?></div>
                                    <input type="text" name="sku" value="<?php echo e($a->sku); ?>" table="contents"
										id="<?php echo e($a->id); ?>" class="sku<?php echo e($a->id); ?> form-control edit" />
								</td>
								<td class="d-none">
									<div class="d-none"><?php echo e($a->kat_sku); ?></div>
                                    <input type="text" name="kat_sku" value="<?php echo e($a->kat_sku); ?>" table="contents"
										id="<?php echo e($a->id); ?>" class="kat_sku<?php echo e($a->id); ?> form-control edit" />
								</td>
								<td width="50" class="d-none">
									<div class="d-none"><?php echo e($a->stok); ?></div>
                                    <input type="text" title="<?php echo e(e2("Başlangıç Stok")); ?>" name="stok" value="<?php echo e($a->stok); ?>" table="contents"
										id="<?php echo e($a->id); ?>" class="stok<?php echo e($a->id); ?> form-control edit" />
									
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
										 <?php echo e($giris-$cikis); ?>

										 <div class="btn-group d-none">
										 	<div title="<?php echo e(e2("Başlangıç Stok + Toplam Stok Girişi")); ?>" class="btn btn-success"><?php echo e($giris); ?></div> 
											 <div class="btn btn-default">-</div>
											<div title="<?php echo e(e2("Toplam Stok Çıkışı")); ?>" class="btn btn-danger"><?php echo e($cikis); ?></div>
											<div class="btn btn-default">=</div>
											<div title="<?php echo e(e2("Kalan Stok:")); ?>" class="btn btn-info"><?php echo e($giris-$cikis); ?></div>
										 </div>
										 
										 
										 
								</td>
								<td width="500">
									<div class="d-none"><?php echo e($a->title); ?></div>
                                    <input type="text" name="title"  value="<?php echo e($a->title); ?>" table="contents"
										id="<?php echo e($a->id); ?>" class="title<?php echo e($a->id); ?> form-control edit" />
								</td>
								<td width="50">
									<div class="d-none"><?php echo e($a->title2); ?></div>
                                    <input type="text" name="title2" value="<?php echo e($a->title2); ?>" table="contents"
										id="<?php echo e($a->id); ?>" class="title2<?php echo e($a->id); ?> form-control edit" />
								</td>
								<td  width="50">
									<div class="d-none"><?php echo e($a->renk); ?></div>
                                    <input type="text" name="renk" value="<?php echo e($a->renk); ?>" table="contents"
										id="<?php echo e($a->id); ?>" class="renk<?php echo e($a->id); ?> form-control edit" />
								</td>
								<td  width="50">
									<div class="d-none"><?php echo e($a->grup); ?></div>
                                    <input type="text" name="grup" value="<?php echo e($a->grup); ?>" table="contents"
										id="<?php echo e($a->id); ?>" class="grup<?php echo e($a->id); ?> form-control edit" />
								</td>
								<td class="d-none">
									<textarea   class="form-control edit" name="alt_type"  table="contents"
										id="<?php echo e($a->id); ?>" id="" cols="30" rows="2"><?php echo e($a->alt_type); ?></textarea>
                                   
								 </td>
								
								 <td class="d-none">
									<?php echo e(username($a->uid,$users)); ?>

								</td>
								<td class="text-center">
									<div class="btn-group"> 
											<!--
											<a href="<?php echo e(url('admin/contents/'. $a->slug)); ?>"
											class="btn btn-secondary js-tooltip-enabled" data-toggle="tooltip" title=""
											data-original-title="Edit"> <i class="fa fa-edit"></i> </a>
											-->
											<a
											href="<?php echo e(url('admin/contents/'. $a->slug .'/delete')); ?>"
											teyit="<?php echo e($a->title); ?> <?php echo e(__('içeriğini silmek istediğinizden emin misiniz?')); ?>"
											title="<?php echo e($a->title); ?> <?php echo e(__('Silinecek!')); ?>"
											class=" btn  btn-danger js-tooltip-enabled" data-toggle="tooltip"
											title="" data-original-title="Delete"> <i class="fa fa-times"></i> </a>
											<a href="?detay=<?php echo e($a->id); ?>" title="<?php echo e(e2("Ürün Detayları")); ?>" class="btn btn-primary">
												<i class="fa fa-list"></i>
											</a>
											<a href="?ajax=print-urun-barkodu&id=<?php echo e($a->id); ?>" title="<?php echo e(e2("Bu ürün için çoklu barkod yazdır")); ?>" target="_blank" class="btn btn-success"><i class="fa fa-print"></i></a>
									</div>
								</td>
								
							</tr> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> </tbody>
					</table> <?php echo e($urunler->appends(request()->except(['page','_token']))->links()); ?>

				</div>
			</div>
		</div>
	</div>
</div><?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/admin/type/urunler.blade.php ENDPATH**/ ?>