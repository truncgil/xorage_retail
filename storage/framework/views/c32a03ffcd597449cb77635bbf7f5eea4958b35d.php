
<?php $__env->startSection("title"); ?>
	<?php echo e($q); ?> <?php echo e(__('Arama Sonuçları')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection("desc"); ?>
<div class="container">
	<form action="<?php echo e(url("admin/search")); ?>" method="get">
		<div class="form-group row">
			<div class="col-lg-12">
				<div class="input-group">
					<div class="input-group-prepend">
						<button type="submit" class="btn btn-primary">
							<i class="fa fa-search"></i> Ara
						</button>
					</div>
					<input type="text" required class="form-control" value="<?php echo e($q); ?>" id="" name="q" placeholder="Ara...">
				</div>
			</div>
		</div>
	</form>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="content">
<div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title"><?php echo e($q); ?> <?php echo e(__('Arama Sonuçları')); ?></h3>
            <div class="block-options">
                <div class="block-options-item">
                </div>
            </div>
        </div>
		

        <div class="block-content">
			<div class="js-gallery ">
			<?php if($search_contents->isEmpty()): ?> 
				<div class="alert alert-info">Herhangi bir şey bulunamadı</div>
			<?php else: ?>
			<div class="table-responsive">
            <table class="table table-striped table-hover table-bordered table-vcenter">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;"><?php echo e(__("Resim")); ?></th>
                        <th><?php echo e(__("Başlık")); ?></th>
                        <th><?php echo e(__("URL")); ?></th>
                        <th><?php echo e(__("Kategorisi")); ?></th>
                        <th class="d-none d-sm-table-cell" style="width: 15%;"><?php echo e(__("Tip")); ?></th>
						<th><?php echo e(__("Durum")); ?></th>
						<th><?php echo e(__("Sıra")); ?></th>
                        <th class="text-center" style="width: 100px;"><?php echo e(__("İşlemler")); ?></th>
                    </tr>
                </thead>
                <tbody>
				
				<?php $__currentLoopData = $search_contents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr class="">
                        <th class="text-center cover" scope="row">
						<?php if($a->cover!=''): ?>
						<a href="<?php echo e(url('cache/large/'.$a->cover)); ?>" class="img-link img-link-zoom-in img-thumb img-lightbox"  target="_blank" >
							<img src="<?php echo e(url('cache/small/'.$a->cover)); ?>" alt="" />
						</a>
						<hr />
						<?php endif; ?>
						<div class="btn-group">
						<button type="button" class="btn  btn-secondary btn-sm" onclick="$('#c<?php echo e($a->id); ?>').trigger('click');" title="<?php echo e(__('Resim Yükle')); ?>"><i class="fa fa-upload"></i></button>
						<?php if($a->cover!=''): ?>
						<a teyit="<?php echo e(__('Resmi kaldırmak istediğinizden emin misiniz')); ?>" title="Resmi kaldır" href="<?php echo e(url('admin-ajax/cover-delete?id='.$a->id)); ?>" class="btn btn-secondary btn-sm "><i class="fa fa-times"></i></a>
						<a title="<?php echo e(__('Resmi indir')); ?>" href="<?php echo e(url('cache/download/'.$a->cover)); ?>" class="btn btn-secondary btn-sm"><i class="fa fa-download"></i></a>
						<?php endif; ?>
						</div>
						<form action="<?php echo e(url('admin-ajax/cover-upload')); ?>" id="f<?php echo e($a->id); ?>"  class="hidden-upload" enctype="multipart/form-data" method="post">
							<input type="file" name="cover" id="c<?php echo e($a->id); ?>" onchange="$('#f<?php echo e($a->id); ?>').submit();" required />
							<input type="hidden" name="id" value="<?php echo e($a->id); ?>" />
							<input type="hidden" name="slug" value="<?php echo e($a->slug); ?>" />
							<?php echo e(csrf_field()); ?>

						</form>
						</th>
                        <td class="text-center">
							<input type="text" name="title" value="<?php echo e($a->title); ?>" table="contents" id="<?php echo e($a->id); ?>" class="title<?php echo e($a->id); ?> form-control edit" />
							<small><?php echo e($a->breadcrumb); ?></small>
						</td>
						<td>
						<div class="input-group">
							<div class="input-group-prepend">
									<div class="btn btn-default" onclick="$.get('<?php echo e(url('admin-ajax/slug?title=')); ?>'+$('.title<?php echo e($a->id); ?>').val(),function(d){
										$('.slug<?php echo e($a->id); ?>').val(d).blur(); location.reload();
									})"><i class="si si-refresh"></i></div>
								</div>
								<input type="text" name="slug" value="<?php echo e($a->slug); ?>" table="contents" id="<?php echo e($a->id); ?>" class="slug<?php echo e($a->id); ?> form-control edit" />
							</div>
							
						</td>
						<td><input type="text" name="kid" value="<?php echo e($a->kid); ?>" table="contents" id="<?php echo e($a->id); ?>" class="form-control edit" /></td>
                        <td class="d-none d-sm-table-cell">
                          
							<select name="type" id="<?php echo e($a->id); ?>" class="select2 form-control edit" table="contents" >
							<?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<option value="<?php echo e($t->title); ?>" <?php if($t->title==$a->type): ?> selected <?php endif; ?>><?php echo e($t->title); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</select>
                        </td>
						<td>
							<select name="y" id="<?php echo e($a->id); ?>" class="select2 form-control edit" table="contents" >
								<option value="0" <?php if($a->y==0): ?> selected <?php endif; ?>><?php echo e(__("Yayında Değil")); ?></option>
								<option value="1" <?php if($a->y==1): ?> selected <?php endif; ?>><?php echo e(__("Yayında")); ?></option>
							</select>
						</td>
 						<td><input type="number" name="s" value="<?php echo e($a->s); ?>" table="contents" id="<?php echo e($a->id); ?>" class="form-control edit" /></td>
                       <td class="text-center">
                            <div class="btn-group">
                                <a href="<?php echo e(url('admin/contents/'. $a->slug)); ?>" class="btn btn-secondary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="<?php echo e(url('admin/contents/'. $a->slug .'/delete')); ?>" teyit="<?php echo e($a->title); ?> içeriğini silmek istediğinizden emin misiniz?" title="<?php echo e($a->title); ?> Silinecek!" class=" btn  btn-secondary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Delete">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				
                                     
                                    </tbody>
            </table>
			</div>
			<?php endif; ?>
			</div>
        </div>
		
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/admin/search.blade.php ENDPATH**/ ?>