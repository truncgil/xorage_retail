<?php 
$firmalar = contents_to_array("Müşteriler");
$urunler = contents_to_array("Ürünler");
$stok_cikis_sayim = stok_cikis_sayim();
 ?>
<div class="content">
    <div class="row">
            @include("admin.type.siparisler.yeni-siparis")
            @include("admin.type.siparisler.siparis-listesi")
        
    </div>

</div>