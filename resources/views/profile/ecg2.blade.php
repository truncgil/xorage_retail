<?php 
$mac = oturum("user")->mac;
$veri = db("pulse_data")
    ->where("mac",$mac)
    ->orderBy("id","DESC")
    ->first();
    //print_R($veri);
    $data = $veri->data;
    $data = explode(",",$data);
    $dizi = array();
    $k=0;
    $sinyal = array();
    $labels = array();
    foreach($data AS $d) {
        $k++;
        if($d!="") {
          if($d<400) {
            $d=500;
          }
            array_push($dizi,"['$k',$d]");
            array_push($sinyal,$d);
            array_push($labels,$k);
            if($k==80) break;
        }
        
    }
    $hasta = db("users")
    ->where("mac",get("id"))
    ->first();
  //  print_R($hasta);

    ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<canvas id="myChart"></canvas>
<script>
$(function(){
  function cihaz_bagli_degil() {
                console.log("toast ok");
                
                
                var toast = app.toast.create({
                text: "Sphyzer'a bağlanılamadı. Lütfen cihaz ile bağlantı kurunuz",
                closeButton: true,
                closeTimeout: 3000,
                on: {
                    opened: function () {
                    console.log('Toast opened')
                    }
                }
                });
                toast.open();
                
            }
  function drawData(data) {
    var ctx = document.getElementById('myChart').getContext('2d');
    var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'line',

        // The data for our dataset
        data: {
            labels: data,
            datasets: [{
                label: 'EKG Sinyali',
                backgroundColor: 'rgba(255,255,255,0)',
                borderColor: '#f4bf2b',
                data: data
            }]
        },

        // Configuration options go here
        options: {}
    });
  }
  function removeData(chart) {
   //   chart.data.labels.pop();
      chart.data.datasets.forEach((dataset) => {
          dataset.data.pop();
      });
      chart.update();
  }
  function addData(chart, data) {
      chart.data.labels.push(data);
      chart.data.datasets.forEach((dataset) => {
          dataset.data.push(data);
      });
      chart.update();
  }

  function getData() {
                $.getJSON('ecg-data',function(d){
                   // console.log(d.data.split(","));
                    console.log(d);
                    if(d.data!==undefined) {
                        drawData(d.data);
                        $(".durum").html("Cihaz bağlantısı başarılı, cihazdan veri alınıyor...");
                        $(".durum-icon").html("wifi");
                        $(".battery").html("%"+d.battery);
                        $(".gps").html(d.lat+","+d.lng);
                        $(".tarih").html(d.created_at);
                    } else {
                        drawData("0,0,0,0");
                        $(".durum").html("Cihaz verisi bekleniyor...");
                        $(".durum-icon").html("wifi_slash");
                        cihaz_bagli_degil();

                    }
                  
                     
                });
            }
    getData();

    window.setInterval(function(){
        getData();
        
    },45000);
});
</script>

    