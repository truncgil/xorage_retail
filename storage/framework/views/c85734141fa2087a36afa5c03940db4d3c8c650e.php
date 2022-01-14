<?php $u = u(); ?>
<div class="content">
<?php if(getisset("t")) {
 ?>
 <?php echo $__env->make("admin.type.barkod.".get("t"), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
 <?php 
       } else  { 
         ?>
   <div class="row text-center">
     
        <?php echo e(col("col-md-12")); ?>

             <img src="<?php echo e(url("assets/yatay.svg")); ?>"  class="img-fluid mb-10" alt="">
             <h1><?php echo e($u->name); ?> <?php echo e($u->surname); ?></h1>
             <div class="btn-group">
                 <a href="<?php echo e(url("logout")); ?>" class="btn btn-warning"><?php echo e(e2("Çıkış Yap")); ?></a>
                 <a href="#" data-toggle="layout" data-action="side_overlay_toggle" class="btn btn-primary"><?php echo e(e2("Profil Düzenle")); ?></a>
             </div>
             
        <?php echo e(_col()); ?>

        <?php echo e(col("col-md-12")); ?>

         <div class="row">
             <div class="col-12">
                
             </div>
             <div class="col-6">
                 <a href="?t=stok-girisi" class="btn btn-success">
                     <i class="fa fa-2x fa-box"></i>
                     <br>
                     <?php echo e(e2("Stok Girişi")); ?>

                 </a>
             </div>
             <div class="col-6">
                 <a href="?t=stok-cikisi" class="btn btn-danger">
                     <i class="fa fa-2x fa-inbox"></i>
                     <br>
                     <?php echo e(e2("Stok Çıkışı")); ?>

                 </a>
             </div>
         </div>
        <?php echo e(_col()); ?> 
       
    
   </div>
   <?php } ?>
</div>
<style>
    #page-header,.bg-image {
        display:none;
    }
</style><?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/admin/type/barkod.blade.php ENDPATH**/ ?>