<?php $sorgu = db("vehicles");
//bu araya filtreleme kısımları gelecek
$dolar = curr("Dolar");
//echo $dolar;
$get = $_GET;
unset($get['best']);
//unset($get['']);

unset($get['category']);
unset($get['category']);
unset($get['mileageto']);
unset($get['page']);
foreach($get AS $alan => $deger) {
	if($deger!="") {
		switch($alan) {
            case "chk_new" : 
                $sorgu = $sorgu->orderBy("id","DESC");
            break;
            case "sort" : 
                if($deger=="price") {
                    $sorgu = $sorgu->orderBy("fob","ASC");
                }
                if($deger=="price2") {
                    $sorgu = $sorgu->orderBy("fob","ASC");
                }
            break;
            case "pfrom" : 
                $sorgu = $sorgu->where("fob",">=",$deger*1000);
            break;
            case "pto" : 

                $sorgu = $sorgu->where("fob","<=",$deger*1000);
            break;

			case "min_fob" : 
				//$sorgu = $sorgu->whereRaw("JSON_EXTRACT(json, '$.price') >= $deger");
				$sorgu = $sorgu->where("fob",">=",$deger);
			break;
			case "max_fob" : 
				//$sorgu = $sorgu->whereRaw("JSON_EXTRACT(json, '$.price') <= $deger");
			//	$sorgu = $sorgu->whereRaw("JSON_EXTRACT(json, '$.price') >= $deger");
				$sorgu = $sorgu->where("fob","<=",$deger);
			break;
			default : 
				$sorgu = $sorgu->where("json->$alan",$deger);
			break;
		}
		
	}
	
}


$sorgu = $sorgu->where("y",1);
$sorgu = $sorgu->orderBy("id","DESC")->simplePaginate(10); ?>

<style>
        .table {
            font-size:13px;
        }
    </style>
<div class="mt-4 mb-4">
    <div class="">{{e2("SEARCH RESULTS")}}</div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <tr>
				<td align="left" nowrap="" class="table_grid_heading">
                {{e2("STOCK NO")}}
                
                </td>
                <td align="left" nowrap="" class="table_grid_heading">{{e2("CAR PHOTO")}}</td>
                
                <td align="left" nowrap="" class="table_grid_heading">{{e2("MAKE <br> MODEL")}}</td>
                <td align="left" nowrap="" class="table_grid_heading">{{e2("GRADE <br> MODEL CODE")}}</td>
                <td align="left" nowrap="" class="table_grid_heading">
                    {{e2("REGISTER <br> PRODUCT YEAR")}} </td>
                <td align="center" nowrap="" class="table_grid_heading">
                    {{e2("COLOR")}}</td>
                <td align="center" nowrap="" class="table_grid_heading">{{e2("MILEAGE (KM)")}}</td>
                <td align="center" nowrap="" class="table_grid_heading">{{e2("CC <br> FUEL")}}</td>
                <td align="left" nowrap="" class="table_grid_heading">{{e2("SHIPPING PORT")}}</td>
                <td align="left" nowrap="" class="table_grid_heading">{{e2("FOB PRICE")}}</td>
                <td align="left" nowrap="" class="table_grid_heading">{{e2("FAVORITE <br> INQUIRY")}}</td>
                <!-- <td align="left" nowrap class="table_grid_heading">Dealer</td> -->
              
            </tr>
            <?php 
					$j = array();
					foreach($sorgu AS $s) { 
						$j = json_decode($s->json,true);
						$files = explode(",",$s->files);
						
						
						?>
						<tr>
							<td align="left" nowrap="nowrap">
							<?php if($j['discount_hesap']!=0) { ?>
								<div class="badge badge-danger">{{e2("DISCOUNT")}}</div> <br>
							<?php } ?>
							<?php if(isset($j['inquiry'])) { ?>
								<div class="badge badge-success">{{e2("UNDER NEGO")}}</div> <br>
							<?php } ?>
								{{$j['stock_no']}}
							</td>
							<td align="center" nowrap="">
							@if(isset($files[0]) && $files[0]!="")
                            <a href="{{url("vehicle?id=".$s->id)}}"><img src="{{pic($files[0],"medium")}}" width="100" height="80" border="0" class="picture_border"></a>
							@endif	
							</td>
							<?php 
							/*
							
							Array
(
    [price] =&gt; 198000
    [tax] =&gt; 0.1
    [tax_total] =&gt; 19800
    [auction] =&gt; MIRAi SAITAMA
    [port] =&gt; US LOGI YOKOHAMA
    [trans] =&gt; 17710
    [forwarding] =&gt; 19500
    [recyle] =&gt; 10720
    [basic] =&gt; 288,330
    [misse] =&gt; 10000
    [service_fee] =&gt; 55000
    [total_fob_price] =&gt; ¥ 353,400
    [total_fob_price_euro] =&gt; € 2,880
    [total_fob_price_dolar] =&gt; $ 3,430
    [_token] =&gt; koeWh9Xn5EogBm52wDHjxDJvEaRur3Uy6LIqHeCd
    [stock_no] =&gt; S20000020
    [fob_price] =&gt; 353,400
    [discount] =&gt; 
    [discount_hesap] =&gt; 0
    [make] =&gt; TOYOTA
    [model] =&gt; COROLLA FIELDER
    [bodytype] =&gt; wagon
    [condition] =&gt; Used
    [chassis] =&gt; NZE161-7008595
    [engine_no] =&gt; 
    [model_code] =&gt; DBA-NZE161G
    [model_grade] =&gt; 1.5G
    [fuel] =&gt; Gasoline
    [traction] =&gt; 2WD (FR) 
    [transmission] =&gt; ATM:Automatic
    [handle] =&gt; Right
    [year] =&gt; 2012
    [month] =&gt; August
    [pyear] =&gt; 2012
    [pmonth] =&gt; August
    [ext_color] =&gt; Blue
    [int_color] =&gt; Black
    [cc] =&gt; 1500
    [milage] =&gt; km
    [mileage] =&gt; 138536
    [doors] =&gt; 5
    [seats] =&gt; 5
    [gross_weight] =&gt; 
    [net_weight] =&gt; 
    [location] =&gt; YOKOHAMA
    [stock_for] =&gt; Array
        (
            [0] =&gt; Kenya
            [1] =&gt; Tanzania
        )

    [length] =&gt; 440
    [width] =&gt; 170
    [height] =&gt; 148
    [m3] =&gt; 11.07
    [remarks] =&gt; 
    [opts] =&gt; Array
        (
            [21] =&gt; 1
            [19] =&gt; 1
            [3] =&gt; 1
            [2] =&gt; 1
            [36] =&gt; 1
            [27] =&gt; 1
            [35] =&gt; 1
            [22] =&gt; 1
            [5] =&gt; 1
            [6] =&gt; 1
        )

    [best_seller] =&gt; best_seller
    [status] =&gt; Available
    [action] =&gt; add
    [sheet1] =&gt; storage/app/files/sheets//IMG_6109.jpg
)
								TOYOTA COROLLA FIELDER                  
							*/
							?>
							<td align="left">
						
								{{@$j['make']}} {{@$j['model']}}                  <br>
							<strong>[
								{{@$j['bodytype']}}                    ]</strong></td>
								<td>{{@$j['model_grade']}}</td>
							<td align="left">
							{{@$j['year']}} / {{@$j['pyear']}} </td>
							<td align="center">
								{{@$j['ext_color']}}</td>
							<td align="center">
								{{mile($j['mileage'],"KM")}}</td>
							<td align="center">{{@$j['cc']}} / {{@$j['fuel']}}</td>
							<td align="left" nowrap="">{{@$j['location']}}</td>
							<td align="left" nowrap="">
							
							{{@$j['total_fob_price_dolar']}}
						
							</td>
							<td></td>
							
						
                        
							
						</tr>
					<?php } ?>
                
        </table>
        
        {{$sorgu->appends(request()->query())->links()}}
    </div>
</div>