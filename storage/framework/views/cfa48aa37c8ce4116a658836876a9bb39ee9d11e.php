<?php use App\Translate; ?>

<?php use App\Types; ?>

<?php $types = Types::whereNull("kid")->orderBy("s","ASC")->get(); ?>

 <div class="content-side content-side-full">

                    

                    <ul class="nav-main">

                        <li>

                            <a class="active" href="<?php echo e(url('admin/')); ?>"><i class="si si-cup"></i><span

                                    class="sidebar-mini-hide"><?php echo e(__('Dashboard')); ?></span></a>

                        </li>

					

						<li class="nav-main-heading d-none"><span class="sidebar-mini-visible">UI</span><span

                                class="sidebar-mini-hidden"><?php echo e(__('Content Types')); ?></span></li>

								

						 <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

						

							<?php if(in_array($c->title,$permissions) || in_array("ALL PRIVILEGES",$permissions)): ?>

								<li>

							<?php $alt = Types::where("kid",$c->slug)->get(); ?>

									<a  <?php if(varmi($alt)) { ?>class="nav-submenu" data-toggle="nav-submenu"<?php } ?>  href="<?php echo e(url('admin/types/'. $c->slug)); ?>"><i class="fa fa-<?php echo e($c->icon); ?>"></i><span><?php echo e(__($c->title)); ?></span></a>

									<?php  if(varmi($alt)) { ?>

								

									<ul>

									<?php foreach($alt AS $a) { ?>

									<?php if(in_array($c->title,$permissions) || in_array("ALL PRIVILEGES",$permissions)): ?>
										<li><a href="<?php echo e(url('admin/types/'. $a->slug)); ?>"><?php echo e($a->title); ?></a></li>

									<?php endif; ?>

									<?php } ?>
									</ul>

									<?php } ?>

								</li>

							<?php endif; ?>

							

						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

						<?php if(in_array("users",$permissions)): ?>

					

                        <li>

                            <a class="nav-submenu" href="<?php echo e(url('admin/users')); ?>"><i class="si si-users"></i><span

                                    class="sidebar-mini-hide"><?php echo e(__('Kullanıcılar')); ?></span></a>                        

                        </li>



						<?php endif; ?>

                        <?php if(in_array("contents",$permissions)): ?>

						<li class="nav-main-heading d-none "><span class="sidebar-mini-visible">UI</span><span

                                class="sidebar-mini-hidden"><?php echo e(__('Contents Tree')); ?></span></li>

							

					<ol class="tree d-none">

  

  

  



						 <?php $__currentLoopData = $contents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

							<?php if($c->title!=""): ?>

								<li>

								<label for="menu-<?php echo($c->id) ?>" ajax=".content-ajax" onclick="location.href='<?php echo e(url("admin/contents/".$c->id)); ?>'"><?php e2($c->title) ?></label>

								<input type="checkbox" slug="<?php e2($c->slug); ?>" class="kategori-tree"  id="menu-<?php echo($c->id) ?>" />

								<ol>

								  

								</ol>

								</li>

                                

							<?php endif; ?>

						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

						</ol>

						<script type="text/javascript">

						$(function(){

							$(".kategori-tree").on("click",function(){

								console.log("ok");

								var bu = $(this);

								bu.parent().children("ol").html("<?php echo e(__('Loading...')); ?>"); 

								$.get('<?php echo e(url('admin-ajax/contents-tree?id=')); ?>'+$(this).attr("slug"),function(d){

									bu.parent().children("ol").html(d);

								});

								//return false;

							});

						});

						</script>

						<style type="text/css">

						.contents-tree * {

							cursor:pointer;

						}

						</style>

						<?php endif; ?> 

						<?php if(in_array("new",$permissions)): ?>

						<li class="nav-main-heading"><span class="sidebar-mini-visible">UI</span><span

                                class="sidebar-mini-hidden"><?php echo e(__('Content Management')); ?></span></li>

                        <li>

                            <a class="nav-submenu" href="<?php echo e(url('admin/new/contents')); ?>"><i class="si si-grid"></i><span

                                    class="sidebar-mini-hide"><?php echo e(__("Contents")); ?></span></a>

                          

                        </li>

                        <li>

                            <a class="nav-submenu" href="<?php echo e(url('admin/new/types')); ?>"><i

                                    class="si si-layers"></i><span class="sidebar-mini-hide"><?php echo e(__('Types')); ?></span></a>

                            

                        </li>

                        <li>

                            <a class="nav-submenu" href="<?php echo e(url('admin/fields')); ?>"><i class="si si-list"></i><span

                                    class="sidebar-mini-hide"><?php echo e(__('Columns')); ?></span></a>

                           

                        </li>

						<?php endif; ?>

						

						<?php if(in_array("languages",$permissions)): ?>

                        <li class="nav-main-heading"><span class="sidebar-mini-visible">UI</span><span

                                class="sidebar-mini-hidden"><?php echo e(__('Language Settings')); ?></span></li>

							

							<?php 	$diller = explode(",","en,tr"); foreach($diller AS $d) { ?>

							<li>

									<a href="<?php echo e(url('admin/languages/'. $d)); ?>"><i class="fa fa-language"></i><span><?php echo e(__($d)); ?></span></a>

								</li>

							<?php } ?>

						<?php endif; ?>

                    </ul>



                </div><?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/admin/inc/menu.blade.php ENDPATH**/ ?>