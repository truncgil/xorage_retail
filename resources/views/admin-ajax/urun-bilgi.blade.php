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
    <script src="{{url("assets/JsBarcode.all.min.js")}}"></script>
    <script src="{{url("assets/qrcode.min.js")}}"></script>
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
        @media print {
            .noprint {
                display:none;
            }
        }
    </style>


      <div class="print">
          <?php if($u->cover!="")  { 
            ?>
           <img src="{{picture2($u->cover,128)}}" alt=""> 
           <?php } ?>
          <div class="title">{{$u->title}}
              <br>
              <small>{{$u->renk}} {{$u->title2}} {{$u->grup}}</small>
          </div>
         <svg id="barcode{{$u->id}}" class="barcode"></svg>
        <div>
            <strong>{{urun_stok_durum($u->id)}}</strong>
        </div>
          <script>
              <?php $son_lokasyon = db("stoklar")->where("type",$u->id)->orderBy("id","DESC")->first(); 
              if($son_lokasyon)  { 
               
               ?>
               $("[name='lokasyon']").val("{{$son_lokasyon->lokasyon}}"); 
               <?php } ?>
          </script>
         <script>
             JsBarcode("#barcode{{$u->id}}", "{{$u->id}}", {
                 format: "CODE128",
                 height:40,
                 displayValue: true
             });
            
         </script>
      </div>  


</body>
</html>