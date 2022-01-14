<script>
$(function(){
    <?php foreach($_GET AS $a => $d) {
         ?>
         $("[name='{{$a}}']").val("{{trim($d)}}");
         <?php 
    } ?>
});
</script>
<div class="card">
    <div class="card-header">{{e2("Search For Used Cars")}}</div>
    <div class="card-body">
        <form class="" method="get">
            <div class="row">
            <?php 
 $make_info = db('vehicles')
 ->select('make', DB::raw('count(*) as total'))
 ->groupBy('make')
 ->get('make','total');
 ?>
            <script>
            var dizi = [];
<?php foreach($make_info AS $m) {
     ?>dizi['{{$m->make}}'] = {{$m->total}}; 
     <?php 
} ?>

            $(function(){
                $("#make option").each(function(){
                    var deger = $(this).html();
                    var sayi = dizi[deger];
                    if(deger!="ALL" & sayi!==undefined) {
                        $(this).html(deger+" ("+sayi+")");
                    }
                    
                });
            });
            </script>
                <div class="col-md-3">
                    {{e2("MAKE")}}
                    <select name="make" id="make" onchange="$('.model').html('').load('?ajax2=ajax.model-select&amp;make='+encodeURI($(this).val()),function(){$('.model').val('').change();})" class="select2 form-control make">
                    <option value="">ALL</option>
                    <option value="TOYOTA">TOYOTA</option>  
                    <option value="HONDA">HONDA</option>  
                    <option value="MAZDA">MAZDA</option>
                    <option value="MITSUBISHI">MITSUBISHI</option>
                    <option value="NISSAN">NISSAN</option>
                    <option value="SUBARU">SUBARU</option>
                    <option value="SUZUKI">SUZUKI</option>
                    <option value="ALFA ROMEO">ALFA ROMEO</option>
                    <option value="ASTON MARTIN">ASTON MARTIN</option>
                    <option value="AUDI">AUDI</option>
                    <option value="AUTO BIANCHI">AUTO BIANCHI</option>
                    <option value="BENTLEY">BENTLEY</option>
                    <option value="BIRKIN">BIRKIN</option>
                    <option value="BMW">BMW</option>
                    <option value="BMW ALPINA">BMW ALPINA</option>
                    <option value="BUICK">BUICK</option>
                    <option value="CADILLAC">CADILLAC</option>
                    <option value="CHEVROLET">CHEVROLET</option>
                    <option value="CHRYSLER">CHRYSLER</option>
                    <option value="CITROEN">CITROEN</option>
                    <option value="CT&amp;T">CT&amp;T</option>
                    <option value="DAEWOO">DAEWOO</option>
                    <option value="DAIHATSU">DAIHATSU</option>
                    <option value="DAIMLER">DAIMLER</option>
                    <option value="DODGE">DODGE</option>
                    <option value="DONKERVOORT">DONKERVOORT</option>
                    <option value="FERRARI">FERRARI</option>
                    <option value="FIAT">FIAT</option>
                    <option value="FORD">FORD</option>
                    
                    <option value="HUMMER">HUMMER</option>
                    <option value="HYUNDAI">HYUNDAI</option>
                    <option value="ISUZU">ISUZU</option>
                    <option value="JAGUAR">JAGUAR</option>
                    <option value="JEEP">JEEP</option>
                    <option value="KIA">KIA</option>
                    <option value="LAMBORGHINI">LAMBORGHINI</option>
                    <option value="LANCIA">LANCIA</option>
                    <option value="LAND ROVER">LAND ROVER</option>
                    <option value="LEXUS">LEXUS</option>
                    <option value="LINCOLN">LINCOLN</option>
                    <option value="LOTUS">LOTUS</option>
                    <option value="MASERATI">MASERATI</option>
                    <option value="MAYBACH">MAYBACH</option>
                    
                    <option value="MERCEDES BENZ">MERCEDES BENZ</option>
                    <option value="MERCURY">MERCURY</option>
                    <option value="MG">MG</option>
                    <option value="MINI">MINI</option>
                    
                    <option value="MITSUOKA">MITSUOKA</option>
                    <option value="MORGAN">MORGAN</option>
                    
                    <option value="OPEL">OPEL</option>
                    <option value="PEUGEOT">PEUGEOT</option>
                    <option value="PONTIAC">PONTIAC</option>
                    <option value="PORSCHE">PORSCHE</option>
                    <option value="RENAULT">RENAULT</option>
                    <option value="ROLLS ROYCE">ROLLS ROYCE</option>
                    <option value="ROVER">ROVER</option>
                    <option value="SAAB">SAAB</option>
                    <option value="SATURN">SATURN</option>
                    <option value="SMART">SMART</option>
                    
                    
                    
                    <option value="TVR">TVR</option>
                    <option value="VENTURY">VENTURY</option>
                    <option value="VOLKSWAGEN">VOLKSWAGEN</option>
                    <option value="VOLVO">VOLVO</option>
                    <option value="YES!">YES!</option>
                                                    
                    </select>
                </div>
                <div class="col-md-3">
                    {{e2("MODEL")}}
                    <select name="model" id="" class="form-control model">
                        <option value="">{{e2("ALL")}}</option>
                <?php if(getisset("model")) {
                    foreach(get_model(get("make")) AS $m) {
                    ?>
                    <option value="{{$m}}">{{$m}}</option>
                    <?php 
                    }
                } ?>
                    </select>
                </div>
                <div class="col-md-3">
                    {{e2("MODEL CODE")}}
                    <input type="text" name="model_code" id="" class="form-control">
                </div>
                <div class="col-md-3">
                    {{e2("STOCK NO")}}
                    <input type="text" name="stock_no" id="" class="form-control">
                </div>
                <div class="col-md-3">
                    {{e2("SHIPPING PORT")}}
                    <?php $dizi = explode("\n","YOKOHAMA
        KOBE
        OSAKA
        KISARAZU
        TOKAI
        IBARAKI"); ?>
                                                    <select name="location" class="form-control" id="location">
                                                        <option value="" selected="selected">Select</option>
                                                        <?php foreach($dizi AS $d) { ?>
                                                        <option value="<?php echo(trim($d)) ?>"><?php echo($d) ?></option>
                                                        <?php } ?>
                                                        
                                                    </select>
                </div>
                <div class="col-md-3">
                    {{e2("TRANSMISSION")}}
                    <select name="transmission" class="form-control" id="transmission">
                        <option value="" selected="selected">{{e2("ALL")}}</option>
                        <option value="4F:Manual">4F (Manual)</option>
                        <option value="5COL:Manual">5COL (Manual)</option>
                        <option value="5F:Manual">5F (Manual)</option>
                        <option value="6F:Manual">6F (Manual)</option>
                        <option value="7F:Manual">7F (Manual)</option>
                        <option value="ATM:Automatic">ATM (Automatic)</option>
                        <option value="CATM:Automatic">CATM (Automatic)</option>
                        <option value="CVT:Automatic">CVT (Automatic)</option>
                        <option value="5AT:Automatic">5AT (Automatic)</option>
                        <option value="7AT:Automatic">7AT (Automatic)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    {{e2("BODY TYPE")}}
                    <select name="bodytype" class="form-control" id="bodytype_id">
                        <option value="" selected="selected">{{e2("ALL")}}</option>
                        <?php foreach(cfg2("body-type") AS $b) {
                                ?><option value="{{$b->slug}}">{{$b->title}}</option><?php 
                        } ?>
                    </select>
                </div>
                <div class="col-md-3">
                    {{e2("KEYWORDS")}}
                    <input class="form-control" name="keywords" type="search" placeholder="{{e2("Search  (Stock ID, Make, Model)")}}" aria-label="Search">
                </div>
                <div class="col-md-4">
                        {{e2("MILLEAGE")}}
                        <div class="input-group">
                            <input type="number" placeholder="{{e2("MIN")}}" name="min_km" id="" class="form-control">
                            <input type="number" placeholder="{{e2("MAX")}}" name="max_km" id="" class="form-control">
                        </div>
                </div>
                <div class="col-md-4">
                        {{e2("CC")}}
                        <div class="input-group">
                            <input type="number" placeholder="{{e2("MIN")}}" MIN="500" name="min_cc" id="" class="form-control">
                            <input type="number" placeholder="{{e2("MAX")}}" MAX="50000000" name="max_cc" id="" class="form-control">
                        </div>
                </div>
                <div class="col-md-4">
                        {{e2("FOB PRICE")}} $
                        <div class="input-group">
                            <input type="number" placeholder="{{e2("MIN")}}" MIN="500" name="min_fob" id="" class="form-control">
                            <input type="number" placeholder="{{e2("MAX")}}" MAX="50000000" name="max_fob" id="" class="form-control">
                        </div>
                </div>
                <div class="col-md-3">
                    {{e2("COLOR")}}
                    <select name="ext_color" class="form-control" id="ext_color">
                        <option value="" selected="selected">Select</option>
                        <option value="Beige">Beige</option>
                        <option value="Black">Black</option>
                        <option value="Blue">Blue</option>
                        <option value="Bronze">Bronze</option>
                        <option value="Brown">Brown</option>
                        <option value="Burgundy">Burgundy</option>
                        <option value="CAPRI BLUE">CAPRI BLUE</option>
                        <option value="Champagne">Champagne</option>
                        <option value="Charcoal">Charcoal</option>
                        <option value="Cherry">Cherry</option>
                        <option value="Cinnamon Bronze Metallic">Cinnamon Bronze Metallic</option>
                        <option value="Cream">Cream</option>
                        <option value="Crystal Black Pearl">Crystal Black Pearl</option>
                        <option value="Dark Blue">Dark Blue</option>
                        <option value="Gold">Gold</option>
                        <option value="Gray">Gray</option>
                        <option value="GRAY BLUE ">GRAY BLUE</option>
                        <option value="Green">Green</option>
                        <option value="Maroon">Maroon</option>
                        <option value="Misty Green Pearl">Misty Green Pearl</option>
                        <option value="Morpho Blue Pearl">Morpho Blue Pearl</option>
                        <option value="Off White">Off White</option>
                        <option value="Orange">Orange</option>
                        <option value="Other">Other</option>
                        <option value="Pearl">Pearl</option>
                        <option value="Pearl White">Pearl White</option>
                        <option value="Pewter">Pewter</option>
                        <option value="Pink">Pink</option>
                        <option value="Premium Crystal Red Metallic">Premium Crystal Red Metallic</option>
                        <option value="Purple">Purple</option>
                        <option value="Red">Red</option>
                        <option value="Silver">Silver</option>
                        <option value="Tan">Tan</option>
                        <option value="Teal">Teal</option>
                        <option value="Titanium">Titanium</option>
                        <option value="Turquoise">Turquoise</option>
                        <option value="White">White</option>
                        <option value="WHITE">WHITE</option>
                        <option value="White Orchid Pearl">White Orchid Pearl</option>
                        <option value="Wine">Wine</option>
                        <option value="Yellow">Yellow</option>
                    </select>
                </div>
                <div class="col-md-3">
                    {{e2("REGISTER YEAR")}}
                    <select name="year"  id="year" class="form-control">
                        <option value=""></option>
                        <?php for($k=1980;$k<=date("Y");$k++) {
                                ?><option value="{{$k}}">{{$k}}</option><?php 
                        } ?>
                    </select>
                </div>
                <div class="col-md-3">
                    {{e2("PRODUCT YEAR")}}
                    <select name="pyear"  id="pyear" class="form-control">
                        <option value=""></option>
                        <?php for($k=1980;$k<=date("Y");$k++) {
                                ?><option value="{{$k}}">{{$k}}</option><?php 
                        } ?>
                    </select>
                </div>
                <div class="col-md-3">
                <nav class="navbar navbar-expand-lg navbar-light bg-default row"  style="background-color: #ffffff;">
                    
                    <div class="collapse navbar-collapse" id="navbarColor02">
                    
                    <style>
                       .search-nav img {
                            width: 24px;
                            display: block;
                            /* margin: 0 auto; */
                            float: left;
                            margin: 0 3px;
                            padding: 0px;
                        }
                        .search-nav .active, .search-nav li:hover {
                            background: #e0cd0059;
                            border-radius: 10px;
                        }    
                        .search-nav li {
                            margin:0 7px;
                        }
                        .search-nav label {
                            padding:5px;

                        }
                    </style>
                    <ul class="navbar-nav mr-auto search-nav text-center">
                        <li class="nav-item active">
                            <label>
                                <input type="checkbox" name="new" id=""> <br>
                                <img src="https://www.flaticon.com/svg/static/icons/svg/996/996587.svg" alt="">
                                {{e2("NEW")}} <span class="sr-only">(current)</span>
                            </label>
                        </li>
                        <li class="nav-item">
                            <label>
                                <input type="checkbox" name="hot_sale" id="" > <br>
                                <img src="https://www.flaticon.com/svg/static/icons/svg/3523/3523662.svg" alt="">
                                {{e2("HOT SALE")}}
                            </label>
                        </li>
                     
                       
                    </ul>
                   
                    </div>
                </nav>
                </div>

            </div>
            
            
            <button class="btn btn-dark my-2 my-sm-0" type="submit">{{e2("Search")}}</button>
        </form>
</div>
</div>

