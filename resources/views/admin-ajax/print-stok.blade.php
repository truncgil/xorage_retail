<?php $stok = db("stoklar")->where("id",get("id"))->orWhere("slug",get("id"))->first();
$urunler = contents_to_array("Ürünler"); 
$urun = $urunler[$stok->type];
$j = j($stok->json);
//print2($j);
//print2($stok);
$barcode = $stok->slug;
//Etiket ölçülerimiz, en:6 cm boy:10,7 cm
?>
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
            width:5.5cm;
            height:10.7cm;
           
            padding:0.5cm;
            font-family:sans-serif;
            position:relative;
            overflow:hidden;
            <?php if(getisset("noprint")) { 
              ?>
             margin:0 auto; 
             <?php } ?>
            
        }
        .print .ozellik * {
            font-size:11px;
        }
        .print .qty * {
            font-size:14px;
        }
        .print .ozellik {
            margin:10px auto;
        }
        .print .qty {
            margin: 10px auto;
        }
        .print .logo {
            width:50%;
        }
        .print th {
            text-align:left;
        }
        #barcode {
            width:100%;
            position:absolute;
            bottom:0px;
            left:0px;
        }
        #qrcode img {
            display: block;
            position: absolute;
            width: 22%;
            top: 10px;
            right: 10px;
        }   

        .title {
            position: absolute;
    transform: rotate(270deg);
    bottom: 207px;
    left: -190px;
    width: 10.7cm;
    /* border: solid; */
    z-index: 1000;
    text-align: center;
    font-family: monospace;
    font-size: 18px;
    font-weight: bold;
        }
        
    </style>
    <div class="print">
    <div class="title">{{e2($urun->title)}}</div>
        <img src="{{url("logo.svg")}}" class="logo" alt="">
        <?php if($barcode!="") { ?>
        <div id="qrcode"></div>
        <script type="text/javascript">
            new QRCode(document.getElementById("qrcode"), 'https://btasentetik.com.tr');
        </script>
        <?php } ?>
       
        <table class="ozellik">
            
            <?php 
            $olmayan = explode(",","UV,PAKET_CİNSİ");
            foreach($j AS $alan=>$deger) {
                if(!in_array($alan,$olmayan)) {
                    $alan2 = str_replace("_"," ",$alan);
                ?>
            <tr>
                <th>{{e2($alan2)}}</th>
                <td>:{{$deger}}</td>
            </tr>
            <?php } ?>
            <?php } ?>
        </table>
        <table class="qty">
            <tr>
                <th>{{e2("GROSS")}}</th>
                <td>:{{$stok->qty}}</td>
            </tr>
            <tr>
                <th>{{e2("TARE")}}</th>
                <td>:{{$stok->dara}}</td>
            </tr>
            <tr>
                <th>{{e2("NET")}}</th>
                <td>:{{$stok->net}}</td>
            </tr>
        </table>
        <?php if($barcode!="") { ?>
        <svg id="barcode"></svg>
        <script>
            JsBarcode("#barcode", "{{$barcode}}", {
                format: "CODE128",
                height:20,
                displayValue: true
            });
            <?php if(!getisset("noprint")) { 
              ?>
             window.print(); 
             <?php } ?>
        </script>
        <?php } ?>
    </div>
</body>
</html>