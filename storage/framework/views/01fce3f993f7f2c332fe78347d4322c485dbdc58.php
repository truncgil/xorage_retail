

<?php $__env->startSection('content'); ?>
<div id="page-container" class="main-content-boxed side-trans-enabled">

            <!-- Main Container -->
            <main id="main-container" style="min-height: 969px;">

                <!-- Page Content -->
                <div class="bg-image" style="background-image: url('back.jpg');">
                    <div class="row mx-0 bg-black-op">
                        <div class="hero-static col-md-6 col-xl-8 d-none d-md-flex align-items-md-end">
                            <div class="p-30 js-appear-enabled animated fadeIn" data-toggle="appear">
                                <p class="font-size-h3 font-w600 text-white">
                                    <?php echo e(e2("Slogan")); ?>

                                </p>
                                <p class="font-italic text-white-op">
                                   <?php echo e(e2("Her Hakkı Saklıdır")); ?> © <span class="js-year-copy js-year-copy-enabled"><?php echo e(e2("Kuruluş Yılı")); ?> -<?php echo e(date('Y')); ?></span>
                                </p>
                            </div>
                        </div>
                        <div class="hero-static text-center col-md-6 col-xl-4 d-flex align-items-center bg-white js-appear-enabled animated fadeInRight" data-toggle="appear" data-class="animated fadeInRight">
                            <div class="content content-full">
                                <!-- Header -->
                                <div class="px-30 py-10">
                                    <a class="link-effect font-w700" href="#">
                                        <img src="<?php echo e(url('logo.svg')); ?>" width="256" class="img-fluid" alt="" />
                                    </a>
                                    <h1 class="h3 font-w700 mt-30 mb-10"><?php echo e(e2("Welcome Yazısı")); ?></h1>
                                    <h2 class="h5 font-w400 text-muted mb-0"><?php echo e(e2("Please sign in")); ?></h2>
                                </div>
                                <!-- END Header -->

                                <!-- Sign In Form -->
                                <!-- jQuery Validation functionality is initialized with .js-validation-signin class in js/pages/op_auth_signin.min.js which was auto compiled from _es6/pages/op_auth_signin.js -->
                                <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
                                <form class="js-validation-signin px-30" action="<?php echo e(route('login')); ?>" method="post" novalidate="novalidate">
								 <?php echo csrf_field(); ?>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input type="text" class="form-control" id="login-username" name="email">
                                                <label for="login-username"><?php echo e(__('Username')); ?></label>
                                            </div>
											<?php if ($errors->has('email')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('email'); ?>
												<div class="alert alert-danger" >
													<strong><?php echo e($message); ?></strong>
												</div>
											<?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input type="password" class="form-control" id="login-password" name="password">
                                                <label for="login-password"><?php echo e(__('Password')); ?></label>
                                            </div>
											<?php if ($errors->has('password')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('password'); ?>
												<div class="alert alert-danger">
													<strong><?php echo e($message); ?></strong>
												</div>
											<?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="login-remember-me" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                                <label class="custom-control-label" for="login-remember-me"><?php echo e(__('Remember Me')); ?></label>
												
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-sm btn-hero btn-alt-primary">
                                            <i class="si si-login mr-10"></i> <?php echo e(e2("Sign In")); ?>

                                        </button>
                                        <div class="mt-30 d-none">
                                            <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="op_auth_signup2.html">
                                                <i class="fa fa-plus mr-5"></i> <?php echo e(e2("Create Account")); ?>

                                            </a>
                                            <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="op_auth_reminder2.html">
                                                <i class="fa fa-warning mr-5"></i> <?php echo e(e2("Forgot Password")); ?>

                                            </a>
                                        </div>
                                    </div>
                                </form>
                                <!-- END Sign In Form -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Page Content -->

            </main>
            <!-- END Main Container -->
        </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/auth/login.blade.php ENDPATH**/ ?>