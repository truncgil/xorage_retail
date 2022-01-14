<?php 
$urunler = contents_to_array("Ürünler"); 
$musteriler = contents_to_array("Müşteriler"); 
$stok_cikis_sayim = stok_cikis_sayim();
$users = usersArray();
$user = u();
?>
<div class="content">
    <img src="{{url("logo.svg")}}" style="    position: absolute;
    width: 300px;
    top: 20px;
    left: 20px;" class="yesprint" alt="">
    <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title"><i class="fa fa-{{$c->icon}}"></i> {{e2("Filtrele")}}</h3>
            </div>
            <div class="block-content">
                <form action="" method="get">
                    <div class="row">
                        <div class="col-md-4">
                            {{e2("ÜRÜN")}} : 
                            <select name="urun" id="" class="form-control select2">
                                <option value="">Seçiniz</option>
                                <?php $sorgu = contents_to_array("Ürünler"); foreach($sorgu AS $m) { ?>
                                <option value="{{$m->id}}">{{$m->title}} {{$m->renk}}</option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            {{e2("İŞLEM TARİHİ BAŞLANGIÇ")}} : 
                            <input type="date" name="date1" required value="{{ed(get("date1"),date("Y-m-d"))}}" id="" class="form-control">
                        </div>
                        <div class="col-md-4">
                            {{e2("İŞLEM TARİHİ BİTİŞ")}} : 
                            <input type="date" name="date2" required value="{{ed(get("date2"),date("Y-m-d"))}}" id="" class="form-control">
                        </div>
                        <div class="col-12 text-center mt-3">
                            <button class="btn btn-primary" name="filtre" value="ok">Filtrele</button>
                        </div>
                    </div>
                </form>
            </div>
            <script>
                $(function(){
                    <?php foreach($_GET AS $alan => $deger) {
                         ?>
                         $("[name='{{$alan}}']").val("{{$deger}}");
                         <?php 
                    } ?>
                });
            </script>
            

        </div>
        <div class="row">
            <?php if(getisset("filtre")) { 
              ?>
             
             {{col("col-md-12","Geçmiş Stok Girişleri",3)}} 
  
  <?php $stoklar = db("stoklar");

  if(!getesit("lokasyon","")) {
      $stoklar = $stoklar->where("lokasyon",get("lokasyon"));
     
  }
  if(!getesit("urun","")) {
      $stoklar = $stoklar->where("type",get("urun"));
     
  }
  if(!getesit("date1","")) $stoklar = $stoklar->whereBetween("created_at",[get("date1"),get("date2")]);
  
  $stoklar = $stoklar->orderBy("id","desc");
 
  $stoklar = $stoklar->get(); ?>

  <div class="table-responsive">
      <table class="table" id="excel">
          <tr>
              <th>{{e2("STOK NO")}}</th>
              
              <th>{{e2("BARKOD")}}</th>
              <th>{{e2("ÜRÜN ADI")}}</th>
              <th>{{e2("MİKTAR")}}</th>
              <th>{{e2("LOKASYON")}}</th>
              <th>{{e2("İŞLEM TARİHİ")}}</th>
              <th>{{e2("PERSONEL")}}</th>
              <th>{{e2("DURUM")}}</th>
              <th>{{e2("İŞLEM")}}</th>
          </tr>
          <?php foreach($stoklar AS $stok) { 
              $j = j($stok->json);
              if(isset($urunler[$stok->type]))  { 
               
               $urun = $urunler[$stok->type];
               
               $u = @$users[$stok->uid];
               ?>
           <tr id="t{{$stok->id}}">
               <td>{{$stok->id}}</td>
               <td>{{$stok->type}}</td>
               <td>{{$urun->title}}</td>
               
               <td>{{$stok->qty}}</td>
               <td>{{$stok->lokasyon}}</td>
               <td>{{date("d.m.Y H:i",strtotime($stok->created_at))}}</td>
               <td>{{$u->name}} {{$u->surname}}</td>
               <td><?php if($stok->cikis!="") {
                    ?>
                    <div class="badge badge-success">
                       <i class="fa fa-check"></i>
                    </div>
                    <?php 
               } ?></td>
               <td>
                   <?php if($user->level=="Admin") {
                        ?>
                        <a href="?sil={{$stok->id}}" ajax="#t{{$stok->id}}" teyit="{{e2("Bu stok bilgisini silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!")}}" class="btn btn-danger"><i class="fa fa-times"></i></a>
                        <?php 
                   } ?>
                   <a href="?ajax=print-stok&id={{$stok->id}}" target="_blank" class="btn btn-success d-none">
                       <i class="fa fa-print"></i>
                   </a>
               </td>
           </tr> 
               <?php } ?>
          <?php } ?>
      </table>
   
  </div>
  {{_col()}}
  {{col("col-md-12","Geçmiş Stok Çıkışları",3)}} 
        <div class="table-responsive">
            <table class="table" id="excel">
                <tr>
                    <th>{{e2("ID")}}</th>
                    <th>{{e2("ÜRÜN ADI")}}</th>
                    <th>{{e2("MİKTAR")}}</th>
                    <th>{{e2("TARİH")}}</th>
                    <th>{{e2("PERSONEL")}}</th>
                    <th>{{e2("SİPARİŞ NO")}}</th>
                </tr>
                <?php $sorgu = db("siparisler");
                if(getisset("filtre")) {
                    if(!getesit("urun","")) $sorgu = $sorgu->where("type",get("urun"));
                    if(!getesit("date1","")) $sorgu = $sorgu->whereBetween("created_at",[get("date1"),get("date2")]);

                }
                $sorgu = $sorgu->orderBy("id","DESC")->simplePaginate(20); 
                
                foreach($sorgu AS $s)  { 
                    if(isset($urunler[$s->type]))  { 
                        $u = @$users[$stok->uid];
                     
                   ?>
                  <tr>
                      <td>{{$s->id}}</td>
                      <td>{{$urunler[$s->type]->title}} {{$urunler[$s->type]->renk}}</td>
                      <td>{{$s->qty}}</td>
                      <td>{{date("d.m.Y H:i",strtotime($s->created_at))}}</td>
                      <td>{{@$u->name}} {{@$u->surname}}</td>
                      <td>{{$s->kid}}</td>
                  </tr>  
                     <?php } ?>
                 <?php } ?>
            </table>

            {{$sorgu->appends($_GET)->links()}}
        </div>     
    
    {{_col()}}
             <?php } ?>
    </div>
    </div>
    
</div>
<script>
                $(function(){
                    $(".firma-sec").on("change",function(){
                        $(".detay").html("Yükleniyor...");
                        $.get("?ajax=siparisler",{
                            id : $(this).val()
                        },function(d){
                            $(".detay").html(d);
                        });
                        
                    });
                }); 
            </script>
           