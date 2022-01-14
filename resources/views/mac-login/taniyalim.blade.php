<?php use App\User; ?>

<div class="page-content login-screen-content">
    <div class="text-center">
        <?php center_logo(); ?>
    </div>
	<div class="login-screen-title">Sizi Tanıyalım</div>
	<form action="mac-login?mac={{get("mac")}}&hash={{get("hash")}}&create" method="POST"> 
    {{csrf_field()}}
    <?php if(getisset("create")) {
        if(postesit("email","")) {
           $_POST['email'] = post("phone");
        }
                                $varmi = db("users")->where("email",post("email"))->orWhere("phone",post("phone"))->first();
                                if($varmi) {
                                    alert("Bu telefon numarası daha önce sistemimize kaydolmuştur. Eğer bir yalnışlık olduğunu düşünüyorsanız bizimle irtibata geçebilirsiniz.");
                                } else {
                                    $user = new User;
                                //    $user->country = post("country");
                                    $user->name = post("name");
                                    $user->surname = post("surname");
                                    $user->email = post("email");
                                    $user->mac = post("mac");
                                    $user->phone = post("phone");
                                    $user->level = "Hasta";
                                    $user->password = kripto(post("password"));
                                    $user->recover = post("password");
                                    $user->save();
                                    $kim = db("users")->where("email",post("email"))->first();
									oturumAc();
                                    oturum("uid",$kim->id);
                                    $_SESSION['user'] = $kim;
									//cihaz bilgisine uid değerini atayalım ve cihazı sahiplenelim
									db("cihazlar")
									->where("mac",get("mac"))
									->update([
											'uid' => $kim->id
										]);
                                    /*
                                    mailtemp(post("email"),"Create Account",array(
                                        "name" => post("name"),
                                        "surname" => post("surname"),
                                        "password" => post("password")
                                    ));
                                    */
                                    yonlendir("profile");
                                }
                            } ?>
		<div class="list">
            <div class="block-header"><strong>{{get("mac")}} </strong>kimlik numarasına sahip Sphyzer cihazını ilk siz sahipleniyorsunuz. Bunun için birkaç bilgi gerekli</div>
			<ul>
				<li class="item-content item-input item-input-with-value">
					<div class="item-inner">
						<div class="item-title item-label">Adınız</div>
						<div class="item-input-wrap">
							<input value="{{post("name")}}" name="name" type="text" placeholder="Adınızı buraya giriniz" required id="demo-username-2" class="input-with-value" />
							<span class="input-clear-button"></span>
						</div>
					</div>
				</li>
				<li class="item-content item-input">
					<div class="item-inner">
						<div class="item-title item-label">Soyadınızı</div>
						<div class="item-input-wrap">
							<input value="{{post("surname")}}" type="text" name="surname" placeholder="Soyadınızı buraya giriniz" required id="demo-password-2" class="" />
						</div>
					</div>
				</li>
				<li class="item-content item-input">
					<div class="item-inner">
						<div class="item-title item-label">Cep Telefonu Numaranız</div>
						<div class="item-input-wrap">
							<input  value="{{post("phone")}}" type="number" name="phone" placeholder="Telefonunuzu buraya giriniz" required id="demo-password-2" class="" />
						</div>
					</div>
				</li>
				<li class="item-content item-input">
					<div class="item-inner">
						<div class="item-title item-label">E-Posta Adresiniz (Varsa)</div>
						<div class="item-input-wrap">
							<input  value="{{post("email")}}" type="email" name="email" placeholder="E-Posta adresinizi buraya giriniz" id="demo-password-2" class="" />
						</div>
					</div>
				</li>
			</ul>
		</div>
		<div class="list">
			<ul>
				<li>
				
                    <button class="button button-fill color-yellow">Devam Et</button>
				</li>
			</ul>
			<div class="block-footer">
			</div>
		</div>
	</form>
</div>
