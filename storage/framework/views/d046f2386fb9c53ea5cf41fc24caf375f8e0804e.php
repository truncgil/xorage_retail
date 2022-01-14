

<?php $__env->startSection("title"); ?>
	<?php echo e(e2("Dashboard")); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

		<div class="content">
			<?php if(u()->level=="Admin")  { 
			  ?>
 			<?php echo e(col("col-md-12","Ürün İstatistikleri",15)); ?>

 			<?php echo $__env->make("admin.type.istatistik.urun-stoklari", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
 			<?php echo e(_col()); ?>

 	 
			 <?php } ?>
			  <?php if(u()->level=="Barkod") {
				   ?>
				   <?php echo $__env->make("admin.type.barkod", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
				   <?php 
			  } ?>
		</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/admin/index.blade.php ENDPATH**/ ?>