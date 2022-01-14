<?php $urunler = contents_to_array("Ürünler"); ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Print</title>
</head>
<body>
    <script src="{{url("assets/JsBarcode.all.min.js")}}"></script>
    <script src="{{url("assets/qrcode.min.js")}}"></script>
    <style>
        body {
           
        }
        .print {
            width:4cm;
            height:3cm;
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
        
    </style>
    <?php foreach($urunler AS $u)  { 
      ?>
     <div class="print">
         <div class="title">{{$u->title}}
             <br>
             <small>{{$u->renk}} {{$u->title2}} {{$u->grup}}</small>
         </div>
        <svg id="barcode{{$u->id}}" class="barcode"></svg>
         
         
        <script>
            JsBarcode("#barcode{{$u->id}}", "{{$u->id}}", {
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
</html>