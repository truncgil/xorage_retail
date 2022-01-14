<?php 
$sayi = 10;
if(postisset("sayi")) {
    $sayi = post("sayi");
}
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
            height:2cm;
           color:black;
            padding:0.1cm;
            font-family:sans-serif;
            position:relative;
            border:solid 1px #000;
            margin:0.1cm;
            float:left;
            overflow:hidden;
            <?php if(getisset("noprint")) { 
              ?>
             margin:0 auto; 
             <?php } ?>
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
    <form action="" method="post" class="noprint">
        <?php echo e(csrf_field()); ?>

        <input type="number" name="sayi" value="<?php echo e($sayi); ?>" id="">
        <button>Adet Yazdır</button>
    </form>
<?php for($k=1;$k<=$sayi;$k++)   { 
 ?>
      <div class="print">
          <div class="title"><?php echo e($u->title); ?>

              <br>
              <small><?php echo e($u->renk); ?> <?php echo e($u->title2); ?> <?php echo e($u->grup); ?></small>
          </div>
         <svg id="barcode<?php echo e($u->id); ?><?php echo e($k); ?>" class="barcode"></svg>
          
          
         <script>
             JsBarcode("#barcode<?php echo e($u->id); ?><?php echo e($k); ?>", "<?php echo e($u->id); ?>", {
                 format: "CODE128",
                 height:40,
                 displayValue: true
             });
            
         </script>
      </div>  
 <?php } ?>
   <script>
       window.print();
   </script>
</body>
</html><?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/admin-ajax/print-urun-barkodu.blade.php ENDPATH**/ ?>