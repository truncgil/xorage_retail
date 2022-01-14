<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<canvas id="canvas" style="   
    border:solid 1px #f3b719;"></canvas>
	<script>
		
        var animasyon;
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
                        $(".durum").html("Cihaz bekleniyor...");
                        $(".durum-icon").html("wifi_slash");
                        cihaz_bagli_degil();

                    }
                  
                     
                });
            }   
            getData();
            window.setInterval(function(){
                getData();
                
            },45000);
            drawData("0,0,0,0");
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
                cancelAnimationFrame(animasyon);
            //    console.log(data);
        
                var EcgData = data;
                var canvas = document.getElementById("canvas");

                var ctx = canvas.getContext("2d");
               
                ctx.canvas.width  = window.innerWidth - 40 ;
                ctx.canvas.height = 200;
                ctx.beginPath();
                var w = canvas.width,
                h = canvas.height,
                speed = 2,
                scanBarWidth = 20,
                i=0,
                data = EcgData.split(','),
                
                color='#f3b719';
                var px = 0;
                var opx = 0;
                var py = h/20;
                var opy = py;
                ctx.strokeStyle = color;
                ctx.lineWidth = 1;
                ctx.setTransform(1,0,0,-1,0,h/1.1);
                
                //console.log(data.length);
                drawWave();
                var rate;
                function drawWave() {
                    px += speed;
                    ctx.clearRect( px,0, scanBarWidth, h);
                    ctx.beginPath();
                    ctx.moveTo( opx,  opy);
                    ctx.lineJoin= 'round';
                    var veri = data[++i>=data.length? i=0 : i++];
                   // console.log(veri);
                   if(veri<450) veri=450;
                    py=(veri/8);
                    ctx.lineTo(px, py);
                    ctx.stroke();
                    opx = px;
                    opy = py;
                    
                    if (opx > w) {px = opx = -speed;}


                  animasyon =   requestAnimationFrame(drawWave);
                    //	 console.log(py);
                }
            }


	</script>