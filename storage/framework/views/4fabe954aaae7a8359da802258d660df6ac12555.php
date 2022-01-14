<?php 
$firmalar = contents_to_array("Müşteriler");
$urunler = contents_to_array("Ürünler");
$stok_cikis_sayim = stok_cikis_sayim();
 ?>
<div class="content">
    <div class="row">
            <?php echo $__env->make("admin.type.siparisler.yeni-siparis", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make("admin.type.siparisler.siparis-listesi", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
    </div>

</div><?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/admin/type/siparisler.blade.php ENDPATH**/ ?>