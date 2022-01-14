<?php 
if(getisset("sablon-sil")) {
    db("contents")
    ->where("type","Ürün Grubu")
    ->where("id",get("sablon-sil"))->delete();
}
$urun_gruplari = contents_to_array("Ürün Grubu"); 
$urunler = contents_to_array("Ürünler");
?>
<div class="content">
    <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title"><i class="fa fa-{{$c->icon}}"></i> {{e2($c->title)}}</h3>
            </div>
            
            <div class="block-content">
                <script>
                $(document).ready(function(){
                    $("#ara").on("keyup", function() {
                        var value = $(this).val().toLowerCase();
                        $("#excel tbody tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                        });
                    });
                });
                </script>
                <input type="text" name="" placeholder="{{e2("Ara...")}}" id="ara" class="form-control">
                <div class="table-responsive">
                    <table class="table" id="excel">
                        <thead>
                            <tr>
                                <th>{{e2("Şablon Adı")}}</th>
                                <th>{{e2("Şablon İçeriği")}}</th>
                                <th>{{e2("İşlem")}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($urun_gruplari AS $u)  { 

                                $json = j($u->json);
                                
                            ?>
                            <tr>
                                <td>{{$u->title}} <div class="d-none">{{str_slug($u->title," ")}}</div></td>
                                <td>
                                    <table class="table">
                                        <?php 
                                        $k = 0;
                                        foreach($json['urun'] AS $urun)  { 
                                            if(isset($urunler[$urun]))  { 
                                            
                                        ?>
                                        <tr>
                                            <td>{{$urunler[$urun]->title}}</td>
                                            <td>{{$json['qty'][$k]}}</td>
                                        </tr>  
                                            <?php } ?>
                                        <?php $k++;  } ?>
                                    </table>    

                                </td>
                                <td>
                                    <a href="?sablon-sil={{$u->id}}" teyit="{{$u->title}} {{e2("isimli şablonu silmek istediğinizden emin misiniz?")}}" class="btn btn-danger"><i class="fa fa-times"></i></a>
                                </td>
                            </tr> 
                            <?php } ?>
                         </tbody>
                    </table>
                </div>
            </div>

            

        </div>

    </div>
</div>