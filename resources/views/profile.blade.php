<?php use App\Contents; 
permission();

$title = u()->name . " " . u()->surname;
$description = "";
$keywords = "";

?>

@extends('layouts.app')

@section("title",$title)
@section("description",$description)
@section("keywords",$keywords)


@section('content')
<?php navbar($title) ?>

<div class="page-content" style="padding-bottom:100px;">
    <div class="block text-align-center">
        <?php center_logo(); ?>
    
    </div>
    <div class="block">
        @include("profile.ecg")
    </div>
    <script>
    var sure= 45;
    
    window.setInterval(function(){
      sure--;
      if(sure==0) sure=45;
        $(".sure").html(sure);
       
        $("#guncelle-progress").attr("data-progress",sure);
      
    },1000);
   
    </script>
    <span class="progressbar-infinite"></span>
   
    
    <div class="list">
        <ul>
          <li>
            <a href="?cihaz-sec" class="external item-link item-content durum-link">
              <div class="item-media"><i class="f7-icons durum-icon">wifi</i></div>
              <div class="item-inner">
                <div class="item-title">
                  <div class="item-header"></div>
               
                  <div class="item-footer durum"></div>
                </div>
             
              </div>
            </a>
          </li>
          <li>
            <a href="" class="item-link item-content">
              <div class="item-media"><i class="f7-icons">arrow_right_arrow_left_circle_fill</i></div>
              <div class="item-inner">
                <div class="item-title">
                  <div class="item-header"></div>
               
                  <div class="item-footer">
                      <span class="sure">45</span> saniye sonra veri güncellemesi yapılacak
                  </div>
                </div>
             
              </div>
            </a>
          </li>
          <li>
            <a href="" class="item-link item-content">
              <div class="item-media"><i class="f7-icons">battery_100</i></div>
              <div class="item-inner">
                <div class="item-title">
                  <div class="item-header battery"></div>
               
                  <div class="item-footer">
                      
                  </div>
                </div>
             
              </div>
            </a>
          </li>
          <li>
            <a href="" class="item-link item-content">
              <div class="item-media"><i class="f7-icons">location_fill</i></div>
              <div class="item-inner">
                <div class="item-title">
                  <div class="item-header gps"></div>
               
                  <div class="item-footer">
                      
                  </div>
                </div>
             
              </div>
            </a>
          </li>
            
          <li>
            <a href="#" class="item-link item-content">
              <div class="item-media"><i class="f7-icons">calendar</i></div>
              <div class="item-inner">
                <div class="item-title">
                  <div class="item-header">Son Veri Gönderme Tarihi</div>
           
                  <span class="tarih"></span>
                  <div class="item-footer">Cihazın sisteme son veri gönderme tarihidir</div>
                </div>
               
              </div>
            </a>
          </li>
                  </ul>
    </div>
    
</div>

<div class="toolbar toolbar-bottom">
  <div class="toolbar-inner">
    <!-- Toolbar links -->
    
    <a href="#" class="link"><i class="f7-icons">phone</i></a>
    <a href="/cihaz-bilgisi"  class="external link"><i class="f7-icons">info_circle_fill</i></a>
  </div>
</div>
@endsection

