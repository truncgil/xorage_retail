<?php 
$u = c(get("id")); ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Print</title>
</head>
<body>
    <script src="<?php echo e(url("assets/JsBarcode.all.min.js")); ?>"></script>
    <script src="<?php echo e(url("assets/qrcode.min.js")); ?>"></script>
    <style>
        body {
           
        }
        .print {
            width:4cm;
           color:black;
            padding:0.1cm;
            font-family:sans-serif;
            position:relative;
            border:solid 1px #000;

             margin:0 auto; 
            text-align:center;
        }
       .title {
           font-size:12px;
       }
        .barcode {
            width:100%;
            height:50px;
        }
        @media  print {
            .noprint {
                display:none;
            }
        }
    </style>


      <div class="print">
          <?php if($u->cover!="")  { 
            ?>
           <img src="<?php echo e(picture2($u->cover,128)); ?>" alt=""> 
           <?php } ?>
          <div class="title"><?php echo e($u->title); ?>

              <br>
              <small><?php echo e($u->renk); ?> <?php echo e($u->title2); ?> <?php echo e($u->grup); ?></small>
          </div>
         <svg id="barcode<?php echo e($u->id); ?>" class="barcode"></svg>
        <div>
            <strong><?php echo e(urun_stok_durum($u->id)); ?></strong>
        </div>
          <script>
              <?php $son_lokasyon = db("stoklar")->where("type",$u->id)->orderBy("id","DESC")->first(); 
              if($son_lokasyon)  { 
               
               ?>
               $("[name='lokasyon']").val("<?php echo e($son_lokasyon->lokasyon); ?>"); 
               <?php } ?>
          </script>
         <script>
             JsBarcode("#barcode<?php echo e($u->id); ?>", "<?php echo e($u->id); ?>", {
                 format: "CODE128",
                 height:40,
                 displayValue: true
             });
            
         </script>
      </div>  


</body>
</html><?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/admin-ajax/urun-bilgi.blade.php ENDPATH**/ ?>