<?php $stoklar = db("stoklar")->where("type",get("id"))
->whereNull("cikis") // çıkışı yapılmayan barkodlar listelensin yalnızca
->orderBy("id","DESC")->get();
 ?>
 {{e2("STOK BARKODU GİRİNİZ")}}
 <select name="stok" id="stok" class="form-control select2 stok-sec">
     <option value="">{{e2("SEÇİNİZ")}}</option>
     <?php foreach($stoklar AS $s) {
     ?>
     <option value="{{$s->id}}">{{$s->slug}} / {{$s->net}}</option>
     <?php 
} ?>
     
<script>
    $(".stok-sec").select2();
    $("#stok").on("change",function(){
        $(".info").html("Yükleniyor...").load("?ajax=print-stok&noprint&id="+$(this).val());
    });
</script>