<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;

		$p = $request->all();

		$rand = rand(111,999);

					$user = new User;

					$user->name = $rand;

					$user->surname = $rand;

					$user->email = $rand;
					
					$user->uid = u()->id;

					$user->phone = $rand;

					$user->password = Hash::make($rand);

					$user->save();

		$return =  back()->with("mesaj","Kullanıcı Eklendi");

		echo $return ; ?><?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/admin-ajax/user-add.blade.php ENDPATH**/ ?>