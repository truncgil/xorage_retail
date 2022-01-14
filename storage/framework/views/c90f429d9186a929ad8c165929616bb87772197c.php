<div class="content">
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title"><i class="fa fa-<?php echo e($c->icon); ?>"></i> <?php echo e(e2($c->title)); ?> <?php echo e(__('İçerikleri')); ?></h3>
			<div class="block-options">
				<div class="block-options-item"> <a
						href="<?php echo e(url('admin-ajax/content-type-blank-delete?type='. $c->title)); ?>"
						teyit="<?php echo e(__('Tüm boş '.$c->title.'  '._('') )); ?>" title="<?php echo e(_('Boş Olan  İçeriklerini Sil')); ?>"
						class="btn btn-danger"><i class="fa fa-times"></i> </a> <a
						href="<?php echo e(url('admin-ajax/content-type-add?type='. $c->title)); ?>" class="btn btn-success"
						title="Yeni <?php echo e($c->title); ?> <?php echo e(_('İçeriği Oluştur')); ?>"><i class="fa fa-plus"></i> </a> </div>
			</div>
		</div>
		<div class="block-content">
			<div class="js-gallery "> <?php echo $__env->make("admin.inc.table-search", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> <div class="table-responsive">
					<table class="table table-striped table-hover table-bordered table-vcenter">
						<thead>
							<tr>
								<th class="text-center d-none" style="width: 50px;"><?php echo e(__("Resim")); ?></th>
								<th><?php echo e(__("Ayar Başlığı")); ?></th>
								<th><?php echo e(__("Ayar İçeriği")); ?></th>
								<th><?php echo e(__("Ayar Türü")); ?></th>
								<th class="d-none" style="width: 15%;"><?php echo e(__("Tip")); ?></th>
								<th class="d-none"><?php echo e(__("Durum")); ?></th>
								<th class="d-none"><?php echo e(__("Sıra")); ?></th>
								<th class="text-center" style="width: 100px;"><?php echo e(__("İşlemler")); ?></th>
							</tr>
						</thead>
						<tbody> <?php $__currentLoopData = $alt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <tr class="">
								<th class="text-center d-none cover" scope="row"> <?php if($a->cover!=''): ?> <a
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
								<td>
                                    <input type="text" name="title" value="<?php echo e($a->title); ?>" table="contents"
										id="<?php echo e($a->id); ?>" class="title<?php echo e($a->id); ?> form-control edit" />
								</td>
								<td>
                                    <input type="text" name="alt_type" value="<?php echo e($a->alt_type); ?>" table="contents"
										id="<?php echo e($a->id); ?>" class="alt_type<?php echo e($a->id); ?> form-control edit" />
								 </td>
								
								<td><input type="text" name="kid" value="<?php echo e($a->kid); ?>" table="contents" id="<?php echo e($a->id); ?>"
										class="form-control edit" /></td>
								<td class="d-none"> <select name="type" id="<?php echo e($a->id); ?>"
										class="select2 form-control edit" table="contents"> <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<option value="<?php echo e($t->title); ?>" <?php if($t->title==$a->type): ?> selected
											<?php endif; ?>><?php echo e($t->title); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> </select> </td>
								<td class="d-none"> <select name="y" id="<?php echo e($a->id); ?>" class=" form-control edit" table="contents">
										<option value="0" <?php if($a->y==0): ?> selected <?php endif; ?>><?php echo e(__("Yayında Değil")); ?></option>
										<option value="1" <?php if($a->y==1): ?> selected <?php endif; ?>><?php echo e(__("Yayında")); ?></option>
									</select> </td>
								<td class="d-none"><input type="number" name="s" value="<?php echo e($a->s); ?>" table="contents" id="<?php echo e($a->id); ?>"
										class="form-control edit" /></td>
								<td class="text-center">
									<div class="btn-group"> <a href="<?php echo e(url('admin/contents/'. $a->slug)); ?>"
											class="btn btn-secondary js-tooltip-enabled" data-toggle="tooltip" title=""
											data-original-title="Edit"> <i class="fa fa-edit"></i> </a> <a
											href="<?php echo e(url('admin/contents/'. $a->slug .'/delete')); ?>"
											teyit="<?php echo e($a->title); ?> <?php echo e(__('içeriğini silmek istediğinizden emin misiniz?')); ?>"
											title="<?php echo e($a->title); ?> <?php echo e(__('Silinecek!')); ?>"
											class=" btn  btn-secondary js-tooltip-enabled" data-toggle="tooltip"
											title="" data-original-title="Delete"> <i class="fa fa-times"></i> </a>
									</div>
								</td>
							</tr> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> </tbody>
					</table> <?php echo e($alt->fragment('alt')->links()); ?>

				</div>
			</div>
		</div>
	</div>
</div><?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/admin/type/ayarlar.blade.php ENDPATH**/ ?>