<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

use App\User;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

		$p = $request->all();

		$user = Auth::user();

			

		try {

			$profile = User::where(['id'=>$p['id']])->first();

			$profile->branslar = implode(",",$p['branslar']);



			$profile->update();

			$return =  back()->with("mesaj","Branşlar başarıyla güncellendi");

		} catch(\Exception $e) {

			$hata = substr($e,0,100);

			$return = back()->with("hata","Branşlar kaydedilmedi. Hata kodu: $hata");

		}

		echo $return;

		