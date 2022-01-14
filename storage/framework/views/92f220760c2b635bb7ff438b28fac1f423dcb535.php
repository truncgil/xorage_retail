
<?php $u = u(); ?>
<footer class="main-footer mobile-footer <?php if($u->level!="Barkod") echo "d-none"; ?>">
            <div class="container">
                <ul class="nav nav-pills nav-justified">
                    <li class="nav-item"><a href="<?php echo e(url("admin")); ?>" class="nav-link waves active waves-effect"><span><i
                                    class="nav-icon fas fa-home"></i> <span
                                    class="nav-text"><?php echo e(e2("Özet")); ?></span></span></a></li>
                    <li class="nav-item"><a href="<?php echo e(url("admin?t=stok-girisi")); ?>"
                            class="nav-link waves waves-effect"><span><i class="nav-icon fa fa-box"></i> <span
                                    class="nav-text"><?php echo e(e2("Giriş")); ?></span></span></a></li>
                    <li class="nav-item centerbutton">
                        <div onclick="location.href='#qrcode'" class="nav-link waves-effect waves"><span
                                class="theme-radial-gradient"><i class="close fas fa-plus"></i> <img
                                    src="<?php echo e(url("assets/icon.svg")); ?>" alt="" class="nav-icon"></span>
                            <div class="nav-menu-popover justify-content-between d-none">
                                    <button onclick="location.href='#qrcode'" type="button"
                                        class="btn btn-lg btn-icon-text"><i
                                            class="fa fa-qrcode size-32 loader"></i><span><?php echo e(e2("Karekod")); ?></span></button>
                                  
                        </div>
                    </li>
                  
                    <li class="nav-item"><a href="<?php echo e(url("admin?t=stok-cikisi")); ?>"
                            class="nav-link waves waves-effect"><span><i class="nav-icon fa fa-inbox"></i> <span
                                    class="nav-text"><?php echo e(e2("Çıkış")); ?></span></span></a></li>
                    <li class="nav-item"><a
                            
                            class="nav-link waves" data-toggle="layout" data-action="side_overlay_toggle"><span><i class="nav-icon fa fa-user"></i> <span
                                    class="nav-text"><?php echo e(e2("Profil")); ?></span></span></a></li>
                </ul>
            </div>
        </footer>
       <?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/admin/inc/mobile-footer.blade.php ENDPATH**/ ?>