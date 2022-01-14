
<?php $__env->startSection("title","İçerik Türleri"); ?>
<?php $__env->startSection("desc","Bu sayfada içerik türlerini yönetebilirsiniz"); ?>
<?php $__env->startSection('content'); ?>

<div class="content">
	<div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title"><?php echo e(__('Mevcut Tipler')); ?></h3>
            <div class="block-options">
                <div class="block-options-item">
                    <a href="<?php echo e(url('admin/action/add/types')); ?>" class="btn btn-default"><i class="fa fa-plus"></i></a>
                </div>
            </div>
        </div>
        <div class="block-content">
            <table class="table table-striped table-hover table-bordered table-vcenter">
                <thead>
                    <tr>
                        <th><?php echo e(__("İkon")); ?></th>
                        <th><?php echo e(__("İkon")); ?></th>
                        <th><?php echo e(__("Başlık")); ?></th>
                        <th><?php echo e(__("Parent")); ?></th>
                        <th><?php echo e(__("URL")); ?></th>
                        <th><?php echo e(__("Order")); ?></th>
                        <th><?php echo e(__("Alanlar")); ?> <small><?php echo e(__('Virgüllerle ayırın')); ?></small></th>
                        <th class="text-center" style="width: 100px;"><?php echo e(__("İşlemler")); ?></th>
                    </tr>
                </thead>
                <tbody>
				<?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr>
						<td class="text-center"><i class="fa fa-<?php echo e($a->icon); ?> fa-2x"></i></td>
                        <td>
						
						<input type="text" name="icon" value="<?php echo e($a->icon); ?>" table="types" id="<?php echo e($a->id); ?>" class="icon form-control edit" /></td>
                        <td><input type="text" name="title" value="<?php echo e($a->title); ?>" table="types" id="<?php echo e($a->id); ?>" class="title<?php echo e($a->id); ?> form-control edit" /></td>
                        <td><input type="text" name="kid" value="<?php echo e($a->kid); ?>" table="types" id="<?php echo e($a->id); ?>" class="title<?php echo e($a->id); ?> form-control edit" /></td>
                        <td>
						<div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="btn btn-default" onclick="$.get('<?php echo e(url('admin-ajax/slug?title='.$a->breadcrumb)); ?>'+$('.title<?php echo e($a->id); ?>').val(),function(d){
															$('.slug<?php echo e($a->id); ?>').val(d).blur();
														})"><i class="si si-refresh"></i></div>
                                                    </div>
                                                    <input type="text" name="slug" value="<?php echo e($a->slug); ?>" table="types" id="<?php echo e($a->id); ?>" class="slug<?php echo e($a->id); ?> form-control edit" />
                                                </div>
							
							</td>
						<td><input type="number" name="s" value="<?php echo e($a->s); ?>" table="types" id="<?php echo e($a->id); ?>" class="title<?php echo e($a->id); ?> form-control edit" /></td>
                       
                        <td>
							<textarea name="fields" table="types" id="<?php echo e($a->id); ?>" class="form-control edit" cols="30" rows="2"><?php echo e($a->fields); ?></textarea>
						</td>
                       
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="<?php echo e(url('admin/types/'. $a->slug)); ?>" type="button" class="btn btn-sm btn-secondary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Edit">
                                    <i class="fa fa-link"></i>
                                </a>
                                <a href="<?php echo e(url('admin/types/'. $a->id.'/delete')); ?>" type="button" teyit="<?php echo e($a->title); ?> tipini silmek istediğinizden emin misiniz?" title="<?php echo e($a->title); ?> Silinecek!" class=" btn btn-sm btn-secondary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Delete">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/admin/new/types.blade.php ENDPATH**/ ?>