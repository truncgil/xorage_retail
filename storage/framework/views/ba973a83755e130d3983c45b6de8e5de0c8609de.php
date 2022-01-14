<?php 
oturumAc();
print2($_SESSION);
print2($_GET);

if(!oturumisset("route")) {
    $_SESSION['route'] = "stok-girisi";
}
if(!getisset("data")) {
    echo "veri okunamadı";
    exit();
}
yonlendir(url("admin?t={$_SESSION['route']}&q={$_GET['data']}"));
?><?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/code.blade.php ENDPATH**/ ?>