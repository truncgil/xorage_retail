<?php 
$urunler = contents_to_array("Ürünler");

$siparisler = db("siparisler")->where("kid",get("id"))->orderBy("id","DESC")->get(); ?>

{{e2("SİPARİŞLER:")}}
<select name="siparis" id="" class="form-control select2 siparis-sec">
<option value="">{{e2("SİPARİŞ SEÇİNİZ")}}</option>
    <?php foreach($siparisler AS $s) { ?>
        <option value="{{$s->id}}" type="{{$s->type}}">{{$urunler[$s->type]->title}} / {{date("d.m.Y H:i",strtotime($s->created_at))}} / {{$s->qty}} </option>
    <?php } ?>
</select>
<div class="siparis-detay"></div>
<script>
    $(".siparis-sec").select2();
    $(".siparis-sec").on("change",function(){
        $(".siparis-detay").html("Yükleniyor...");
        $.get("?ajax=siparis-detay",{
            id : $('option:selected', this).attr('type')
        },function(d){
            $(".siparis-detay").html(d);
        });
        
        
    }); 
</script>