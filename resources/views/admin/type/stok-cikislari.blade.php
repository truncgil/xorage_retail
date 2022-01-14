<?php 
$user = u();
$urunler = contents_to_array("Ürünler"); 
$musteriler = contents_to_array("Müşteriler"); 
$users = usersArray();

?>
<div class="content">
    <div class="row">
        
        

    {{col("col-md-12","Filtrele")}}
    <form action="" method="get">
                    <div class="row">
                       
                        <div class="col-md-6">
                            {{e2("ÜRÜN")}} : 
                            <select name="urun" id="" class="form-control select2">
                                <option value="">{{e2("TÜMÜ")}}</option>
                                <?php $sorgu = contents_to_array("Ürünler"); foreach($sorgu AS $m) { ?>
                                <option value="{{$m->id}}">{{$m->title}} {{$m->renk}}</option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            {{e2("PERSONEL")}} : 
                            <select name="uid" id="" class="form-control select2">
                                <option value="">{{e2("TÜMÜ")}}</option>
                                <?php foreach($users AS $us) { ?>
                                <option value="{{$us->id}}" <?php if(getesit("uid",$us->id)) echo "selected"; ?>>{{$us->name}} {{$us->surname}}</option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            {{e2("İŞLEM TARİHİ BAŞLANGIÇ")}} : 
                            <input type="date" name="date1"  value="{{get("date1")}}" id="" class="form-control">
                        </div>
                        <div class="col-md-6">
                            {{e2("İŞLEM TARİHİ BİTİŞ")}} : 
                            <input type="date" name="date2"  value="{{get("date2")}}" id="" class="form-control">
                        </div>
                       
                       
                        <div class="col-md-12 text-center">
                            <button class="btn btn-primary mt-10" name="filtre" value="ok">{{e2("FİLTRELE")}}</button>
                        </div>
                    </div>
                    
                   
                    
                    
                    

                </form>
    {{_col()}}
    {{col("col-md-12","Geçmiş Stok Çıkışları",3)}} 
        <div class="table-responsive">
            <table class="table" id="excel">
                <tr>
                    <td>{{e2("ID")}}</td>
                    <td>{{e2("Ürün Adı")}}</td>
                    <td>{{e2("Miktar")}}</td>
                    <td>{{e2("Tarih")}}</td>
                    <td>{{e2("Personel")}}</td>
                    <td>{{e2("Sipariş No")}}</td>
                </tr>
                <?php $sorgu = db("siparisler");
                if(getisset("filtre")) {
                    if(!getesit("urun","")) $sorgu = $sorgu->where("type",get("urun"));
                    if(!getesit("uid","")) $sorgu = $sorgu->where("uid",get("uid"));
                    if(!getesit("date1","")) $sorgu = $sorgu->whereBetween("created_at",[get("date1"),get("date2")]);

                }
                $sorgu = $sorgu->orderBy("id","DESC")->simplePaginate(20); 
                
                foreach($sorgu AS $s)  { 
                    $personel = @$users[$s->uid];
                    if(isset($urunler[$s->type]))  { 
                     
                   ?>
                  <tr>
                      <td>{{$s->id}}</td>
                      <td>{{$urunler[$s->type]->title}} {{$urunler[$s->type]->renk}}</td>
                      <td>{{$s->qty}}</td>
                      <td>{{date("d.m.Y H:i",strtotime($s->created_at))}}</td>
                      <td>{{@$personel->name}} {{@$personel->surname}}</td>
                      <td>{{$s->kid}}</td>
                  </tr>  
                     <?php } ?>
                 <?php } ?>
            </table>

            {{$sorgu->appends($_GET)->links()}}
        </div>     
    
    {{_col()}}
    </div>
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

