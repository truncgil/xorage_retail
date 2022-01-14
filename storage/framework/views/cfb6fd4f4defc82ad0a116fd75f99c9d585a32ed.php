

<?php $__env->startSection("title"); ?>
<i class="fa fa-<?php echo e($c->icon); ?>"></i> <?php echo e(@e2($c->title)); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection("desc",@$c->title." ". __("türüne ait içerikler")); ?>
<?php $__env->startSection('content'); ?>
<?php $slug = str_slug($c->title); ?>
<?php if(View::exists("admin.type.$slug")): ?> 
		<?php echo $__env->make("admin.type.$slug", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php else: ?> 
	<?php echo $__env->make("admin.type.default", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/admin/types.blade.php ENDPATH**/ ?>