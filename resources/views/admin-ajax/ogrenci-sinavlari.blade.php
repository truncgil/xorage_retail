<?php $ogrenci = db2("ogrenciler")->where("id",get("id"))->first();
$hash = $ogrenci->tc_kimlik_no;
if($hash=="") {
    $hash = $ogrenci->title;
}

$sonuclar = db2("sonuclar")
->where("ogrenci_adi",$ogrenci->title)
->orWhere("tc_kimlik_no",$ogrenci->tc_kimlik_no)
->orderBy("id","DESC")
->get();

$sinav = table_to_array2("sinavlar");
?>
<div class="float-right">
        <strong>{{e2("T.C. Kimlik No")}}: </strong>{{$ogrenci->tc_kimlik_no}}
        <br>
        <strong>{{e2("ID")}}:</strong> {{$ogrenci->id}}
        <br>
        <a href="?ajax=taksonomik-duzey-rapor&ogrenci={{$hash}}" target="_blank" class="btn btn-success">{{e2("Genel Taksonomik Analizi")}}</a>
</div>
<h1>{{$ogrenci->title}}</h1>

<div class="row">
    <div class="col-12">
    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{e2("SINAV ADI")}}</th>
                                <th>{{e2("TARİH")}}</th>
                                <th>{{e2("KİTAPÇIK")}}</th>
                                <th>{{e2("ANALİZ SONUÇLARI")}}</th>
                            </tr>
                        
                        </thead>
                        <tbody>
                        <?php foreach($sonuclar AS $s) { 
                            $analiz = json_decode($s->analiz,true);
                            $this_sinav = $sinav[$s->sinav_id];
                            ?>
                            <tr>
                              
                                <td>{{$this_sinav->title}}</td>
                                <td>{{df($this_sinav->date)}}</td>
                                <td>{{$s->kitapcik}}</td>
                                <td>
                                    <button data-toggle="collapse" class="btn btn-primary" data-target="#sonuc{{$s->id}}">{{e2("Sonuçları Göster/Gizle")}}</button>
                                    <a href="{{url("admin-ajax/sinav-sonuc-belgesi?id=".$s->id)}}" target="_blank" class="btn btn-secondary">{{e2("Kazanımlı Sınav Sonuç Belgesi")}}</a>
                                    <a href="{{url("admin-ajax/taksonomik-duzey-rapor?id=".$s->id)}}" target="_blank" class="btn btn-success">{{e2("Taksonomik Analiz Raporu")}}</a>
                                    <div id="sonuc{{$s->id}}" class="collapse">
                                    
                                    
                                        <table class="table table-striped table-hover table-sm">
                                            <tr>
                                                <th>{{e2("Ders Adı")}}</th>
                                                <th>{{e2("Doğru")}}</th>
                                                <th>{{e2("Yanlış")}}</th>
                                                <th>{{e2("Boş")}}</th>
                                                <th>{{e2("Cevaplar")}}</th>
                                        
                                            </tr>
                                            <?php foreach($analiz AS $alan => $deger) {
                                        ?>
                                            <tr>
                                                <td>{{slug_to_title($alan)}}</td>
                                                <td>
                                                    <div class="badge badge-success">{{$deger['dogru']}}</div> <br>
                                                
                                                    {{implode(", ",$deger['kazanim-dogru'])}} <br>
                                                    {{implode(", ",$deger['tak-dogru'])}} <br>
                                                </td>
                                                <td>
                                                <div class="badge badge-danger">{{$deger['yanlis']}}</div> <br>
                                                
                                                    {{implode(", ",$deger['kazanim-yanlis'])}} <br>
                                                    {{implode(", ",$deger['tak-yanlis'])}} <br>
                                                </td>
                                                <td>
                                                <div class="badge badge-warning">{{$deger['bos']}}</div> <br>
                                                
                                                    {{implode(", ",$deger['kazanim-bos'])}} <br>
                                                    {{implode(", ",$deger['tak-bos'])}} <br>
                                                </td>
                                                <td>{{$deger['cevaplar']}}</td>
                                            </tr>
                                            <?php 
                                    } ?>
                                        </table>
                                     </div>
                                     
                                </td>
                            </tr>
                            <?php } ?>
                     
                </tbody>
            </table>
    </div>
</div>