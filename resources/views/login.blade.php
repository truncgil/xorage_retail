<?php 
use App\Contents; 
use Illuminate\Support\Facades\Auth;
$title = _("Sign In");
$description = _("Sign In This Page");
$keywords = "sign in";

?>

@extends('layouts.app')

@section("title",$title)
@section("description",$description)
@section("keywords",$keywords)


@section('content')


    <div class="container bg-white" style="padding-top:20px;">
    <div id="login-row" class="row justify-content-center align-items-center pb-4">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                    <?php if(getisset("login")) {
                        $u = db("users")->where("email",post("email"))->first();
                        if($u) {
                            if (Hash::check(post("password"), $u->password, [])) {
                                oturumAc();
                                oturum("uid",$u->id);
                                $_SESSION['user'] = $u;
                                yonlendir("profile");
                            } else {
                                alert("You entered your password incorrectly","warning");
                            }
                        } else {
                            alert("E-mail address is not registered in our system.","info");
                        }
                        
                    } ?>
                        <form id="login-form" class="form" action="?login" method="post">
                            <h3 class="text-center text-primary">{{e2("Login")}}</h3>
                            {{csrf_field()}}
                            <div class="form-group">
                                <label for="username" class="text-primary">{{e2("Username")}}:</label><br>
                                <input type="text" name="email" id="email" value="{{post("email")}}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-primary">{{e2("Password")}}:</label><br>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="remember-me" class="text-primary"><span>{{e2("Remember me")}}</span>&nbsp;<span><input id="remember-me" name="remember-me" type="checkbox"></span></label><br>
                                <input type="submit" name="submit" class="btn btn-primary btn-md" value="submit">
                            </div>
                            <div id="register-link" class="text-right">
                                <a href="register" class="text-primary">{{e2("Register here")}}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
           
    </div>
        


    

@endsection

