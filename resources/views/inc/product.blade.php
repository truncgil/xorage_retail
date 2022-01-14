<?php 
$title =$v->title;
$j = j($v->json);
$price = $j['total_fob_price_dolar'];
$save = $j['discount'];
$resim = explode(",",$v->files);
$img = url("cache/medium/{$resim[0]}");
$id = $v->id;
$img = str_replace("storage/app/files/","",$img); ?>
      <div class="card mt-2 product" onclick="location.href='vehicle?id={{$id}}'">
        <a href="vehicle?id={{$id}}">
          <img class="card-img" src="{{$img}}" alt="{{e2($title)}}">
        </a>
        <div class="card-img-overlay d-flex justify-content-end">
          <a href="" class="card-link text-danger like">
            <i class="fas fa-heart"></i>
          </a>
        </div>
        <div class="card-body">
          <a href="vehicle?id={{$id}}">
            <h4 class="card-title" style="height:50px;overflow:hidden">{{e2($title)}}</h4>
            <p class="card-text d-none">
              </p>
          
            <div>
              <div class="price text-success mb-1"><h5 class="">{{$price}}</h5></div>
              <?php if($save>0) { ?>
                <div class="price text-danger">Save {{$save}}%</div>
              <?php } ?>
            </div>
          </a>
        </div>
      </div>
  