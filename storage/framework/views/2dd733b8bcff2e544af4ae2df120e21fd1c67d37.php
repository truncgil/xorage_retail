<?php $__env->startSection("title",__("Kullanıcılar")); ?>
<?php $__env->startSection("desc",__("Sistemde yer alan kullanıcıları bu bölümden yönetebilirsiniz")); ?>
<?php $__env->startSection('content'); ?>
<?php
	use Illuminate\Support\Facades\Request;
	use Illuminate\Support\Facades\Input;
	use App\Contents;
	use App\Types;
	use App\Fields;
	use App\User;
	try{
		$seviye = Contents::where("slug","user-level")->first();
		$seviye = strip_tags($seviye->html);
		$seviye = explode(",",$seviye);
	} catch (Exception $e) {
		
	}
	$seviye = "Admin
	Çalışan
	Barkod
	";
	$seviye = explode("\n",$seviye);
	$request = null;
	if(Input::has("q")) {
		  $request = Request::all();
		  $q = $request['q'];
			
		  $searchFields = ['name','surname','email','phone','permissions'];
		  $users = User::where(function($query) use($request, $searchFields){
			$searchWildcard = '%' . $request['q'] . '%';
			foreach($searchFields as $field){
			  $query->orWhere($field, 'LIKE', $searchWildcard);
			}
		  })
		  ->where("id",">=",Auth::user()->id)
		  ->simplePaginate(10);

	} else {
		$users = User::orderBy("id","DESC")->where("id",">=",Auth::user()->id);
		if(u()->level!="Admin") {
			$users = $users->where("uid",u()->id);
		}
		$users = $users->simplePaginate(5);
	}
	
	$types = Types::all();
	
?>
<div class="content">
<div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">
			
				<form action="" method="get">
					<div class="row">
						<div class="col-md-6">
							<div class="input-group">
								<div class="input-group-prepend">
									<button type="submit" class="btn btn-secondary">
										<i class="fa fa-search"></i>
									</button>
								</div>
								<input type="text" class="form-control"  name="q" value="<?php echo e(@$request['q']); ?>" placeholder="<?php echo e(e2("Kullanıcı Adı")); ?>">
							</div>
						</div>
					</div>
				</form>
			
			</h3>
            <div class="block-options">
                <div class="block-options-item">
                    <a href="<?php echo e(url('admin-ajax/user-add')); ?>" class="btn btn-default"><i class="fa fa-plus"></i></a>
                </div>
            </div>
        </div>
		

        <div class="block-content">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<tr>
						<td><?php echo e(e2("Kimlik")); ?></td>
						<td><?php echo e(e2("Üst")); ?></td>
						<td><?php echo e(e2("Adı")); ?></td>
						<td><?php echo e(e2("Soyadı")); ?></td>
						<td><?php echo e(e2("Seviye")); ?></td>
						<td><?php echo e(e2("E-Mail")); ?></td>
						<td><?php echo e(e2("Telefon")); ?></td>
						<td><?php echo e(e2("Yetkiler")); ?></td>
						<td><?php echo e(e2("Branşlar")); ?></td>
						<td><?php echo e(e2("Şifre")); ?></td>
						<td><?php echo e(e2("Kurtarma Şifresi")); ?></td>
						<td><?php echo e(e2("Etki Alanı")); ?></td>
						<td><?php echo e(e2("İşlem")); ?></td>
					</tr>
					<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php
							$permissions = explode(",",$u->permissions);
						?>
					<tr>
						<td><?php echo e($u->id); ?></td>
						<td><input type="text" name="ust" value="<?php echo e($u->ust); ?>" style="min-width:100px" table="users" id="<?php echo e($u->id); ?>" class="name<?php echo e($u->id); ?> form-control edit" /></td>
						<td><input type="text" name="name" value="<?php echo e($u->name); ?>" table="users" id="<?php echo e($u->id); ?>" class="name<?php echo e($u->id); ?> form-control edit" /></td>
						<td><input type="text" name="surname" value="<?php echo e($u->surname); ?>" table="users" id="<?php echo e($u->id); ?>" class="surname<?php echo e($u->id); ?> form-control edit" /></td>
						<td>
							<select name="level" id="<?php echo e($u->id); ?>" table="users" class="form-control edit">
							<?php if($seviye!=null): ?>
								<?php $__currentLoopData = $seviye; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php $l = trim($l); ?>
								<?php 
								if($l=="Admin") { //eğer admin kullanıcısı ise admin gösterimine izin ver.
									if(u()->level=="Admin") {  ?>
									<option value="<?php echo e($l); ?>" <?php if($u->level==$l): ?> selected <?php endif; ?>><?php echo e(e2($l)); ?></option>
									<?php } ?>
								<?php } else {
									 ?>
									 <option value="<?php echo e($l); ?>" <?php if($u->level==$l): ?> selected <?php endif; ?>><?php echo e(e2($l)); ?></option>
									 <?php 
								} ?>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<?php endif; ?>
							</select>
						</td>
						<td><input type="text" name="email" value="<?php echo e($u->email); ?>" table="users" id="<?php echo e($u->id); ?>" class="email<?php echo e($u->id); ?> form-control edit" /></td>
						<td><input type="text" name="phone" value="<?php echo e($u->phone); ?>" table="users" id="<?php echo e($u->id); ?>" class="phone<?php echo e($u->id); ?> form-control edit" /></td>
						<td>
			<?php // print_r( $permissions); ?>
						<form action="<?php echo e(url('admin-ajax/permission-update')); ?>" method="post">
							<?php echo csrf_field(); ?>
							<select name="permissions[]" multiple id="" class="select2" style="width:250px">
							<?php if($types!=null): ?>
							<?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<?php 
							$ust = "";
							if($t->kid!="") {
								$ust = slugtotitle($t->kid). " / ";
							} ?>
								<option value="<?php echo e($t->title); ?>" <?php if(in_array($t->title,$permissions)): ?> selected <?php endif; ?>><?php echo e($ust); ?><?php echo e($t->title); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<?php endif; ?>
							<?php $__currentLoopData = diger_ayarlar(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
							
								<option value="<?php echo e($d); ?>" <?php if(in_array($d,$permissions)): ?> selected <?php endif; ?>><?php echo e($d); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</select>
							<input type="hidden" name="id" value="<?php echo e($u->id); ?>" />
							<button class="btn btn-default" title="<?php echo e(__('Kullanıcının yetkilerini güncelle')); ?>"><i class="fa fa-sync"></i></button>
						</form>
						</td>
						<td>
							
							<form action="<?php echo e(url('admin-ajax/branslar-update')); ?>" method="post">
								<?php echo csrf_field(); ?>
								<select name="branslar[]" multiple id="" class="select2" style="width:250px">
								
								<?php 
								$brans_list = explode(",",$u->branslar);
								$branslar = branslar(); ?>
								<?php $__currentLoopData = $branslar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								
									<option value="<?php echo e($b->title); ?>" <?php if(in_array($b->title,$brans_list)): ?> selected <?php endif; ?>><?php echo e($b->title); ?></option>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							
								
								</select>
								<input type="hidden" name="id" value="<?php echo e($u->id); ?>" />
								<button class="btn btn-default" title="<?php echo e(__('Öğretmenin branşlarını güncelle')); ?>"><i class="fa fa-sync"></i></button>
							</form>
						</td>
						<td><a href="<?php echo e(url('admin-ajax/password-update?id='.$u->id)); ?>" title="<?php echo e(__('Kullanıcının şifresini sıfırla')); ?>" class="btn btn-default"><i class="fa fa-sync"></i> <?php echo e(e2("Şifre Sıfırla")); ?></button></td>
						<td><?php echo e($u->recover); ?></td>
						<td><input type="text" name="alias" value="<?php echo e($u->alias); ?>" table="users" id="<?php echo e($u->id); ?>" class="alias<?php echo e($u->id); ?> form-control edit" /></td>

						<td>
						<div class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php echo e(e2("İşlemler")); ?>

						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							<a class="dropdown-item" href="#"><i class="fa fa-lock"></i> <?php echo e(e2("Giriş Yap")); ?></a>
							<a class="dropdown-item" teyit="<?php echo e($u->adi); ?> {$u->soyadi} <?php echo e(e2("Kullanıcısını silmek istediğinizden emin misiniz?")); ?>" href="<?php echo e(url('admin-ajax/user-delete?id='.$u->id)); ?>">
							<i class="fa fa-times"></i>
							<?php echo e(e2("Sil")); ?></a>
						  </div>
						</div>
						</td>
					</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</table>
				<?php echo e($users->fragment('users')->links()); ?>

			</div>
		</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/admin/users.blade.php ENDPATH**/ ?>