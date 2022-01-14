<?php $u = u(); ?>
<div class="content">
<?php if(getisset("t")) {
 ?>
 @include("admin.type.barkod.".get("t"))
 <?php 
       } else  { 
         ?>
   <div class="row text-center">
     
        {{col("col-md-12")}}
             <img src="{{url("assets/yatay.svg")}}"  class="img-fluid mb-10" alt="">
             <h1>{{$u->name}} {{$u->surname}}</h1>
             <div class="btn-group">
                 <a href="{{url("logout")}}" class="btn btn-warning">{{e2("Çıkış Yap")}}</a>
                 <a href="#" data-toggle="layout" data-action="side_overlay_toggle" class="btn btn-primary">{{e2("Profil Düzenle")}}</a>
             </div>
             
        {{_col()}}
        {{col("col-md-12")}}
         <div class="row">
             <div class="col-12">
                
             </div>
             <div class="col-6">
                 <a href="?t=stok-girisi" class="btn btn-success">
                     <i class="fa fa-2x fa-box"></i>
                     <br>
                     {{e2("Stok Girişi")}}
                 </a>
             </div>
             <div class="col-6">
                 <a href="?t=stok-cikisi" class="btn btn-danger">
                     <i class="fa fa-2x fa-inbox"></i>
                     <br>
                     {{e2("Stok Çıkışı")}}
                 </a>
             </div>
         </div>
        {{_col()}} 
       
    
   </div>
   <?php } ?>
</div>
<style>
    #page-header,.bg-image {
        display:none;
    }
</style>