<?php 
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
// OR
use Artesaos\SEOTools\Facades\SEOTools;
if(getisset("ajax")) {
	?>
	@include("admin-ajax.{$_GET['ajax']}")
	<?php
	exit();
} ?>
<?php if(getisset("ajax2")) { //blade ajax system
	?>
	@include("{$_GET['ajax2']}")
	<?php
	exit();
} ?>
@php
	use App\Contents;
	oturumAc();
	if(isset($_GET['l'])) {
		app()->setLocale($_GET['l']);
		oturum("locale",$_GET['l']);
	}

@endphp
<!DOCTYPE html>
<html dir="ltr" class="android device-pixel-ratio-1 theme-dark color-theme-blue ">
  <head>
    <!-- Required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <!-- Color theme for statusbar (Android only) -->
    <meta name="theme-color" content="#052136">
    <!-- Your app title -->
    <title>@yield("title")</title>
    <!-- Path to Framework7 Library Bundle CSS -->
    <link rel="stylesheet" href="{{url("assets/framework7/framework7-bundle.min.css")}}">

    <link rel="stylesheet" href="{{url("assets/framework7-icons/css/framework7-icons.css")}}">
    <!-- Path to your custom app styles-->
    <link rel="stylesheet" href="{{url("assets/dijimind.css")}}">
  </head>
  <body>
    <!-- App root element -->
    <div id="app">
   
      <!-- Your main view, should have "view-main" class -->
      <div class="view view-main">
          @yield("content")     
      </div>
    </div>
    <!-- Path to Framework7 Library Bundle JS-->
    <script type="text/javascript" src="{{url("assets/framework7/framework7-bundle.min.js")}}"></script>
    <!-- Path to your app js-->
    <script type="text/javascript" src="{{url("assets/dijimind.js")}}"></script>
  </body>
</html>
		
	
</body>

</html>


