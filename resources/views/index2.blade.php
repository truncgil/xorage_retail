<?php use App\Contents; ?>

@extends('layouts.app')

@section("title","Hoşgeldiniz")
 
    

@section('content')
<!-- Initial Page, "data-name" contains page name -->

<div data-name="home" class="page">





<!-- Scrollable page content -->
<div class="page-content">
<div class="block text-align-center">
    <img src="{{url("assets/logo.svg")}}" alt=""> <br>
   
</div>

<div class="block text-align-center">
  Çok Yakında!
</div>
</div>
</div>


    
@endsection

