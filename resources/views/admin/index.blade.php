@extends('admin.master')

@section("title")
	{{e2("Dashboard")}}
@endsection
@section('content')

		<div class="content">
			<?php if(u()->level=="Admin")  { 
			  ?>
 			{{col("col-md-12","Ürün İstatistikleri",15)}}
 			@include("admin.type.istatistik.urun-stoklari")
 			{{_col()}}
 	 
			 <?php } ?>
			  <?php if(u()->level=="Barkod") {
				   ?>
				   @include("admin.type.barkod")
				   <?php 
			  } ?>
		</div>

@endsection
