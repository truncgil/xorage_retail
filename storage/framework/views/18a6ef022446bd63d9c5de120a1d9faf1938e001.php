<div class="row">
	<div class="col-2">
	<?php $dizi = array(5,10,15,20,25,30,50,100) ?>
		<select onchange="location.href='?m='+$(this).val();" name="" id="" class="form-control ">
		<?php foreach($dizi AS $d) { ?>
			<option value="<?php echo $d ?>" <?php if($miktar==$d) echo("selected"); ?>><?php echo e(__('Her sayfada')); ?> <?php echo $d ?> <?php echo e(__('Satır')); ?></option>
		<?php } ?>
		</select>
	</div>
	<div class="col-md-3 ml-auto">
		<form action="" method="get">
			<input type="text" name="q" id="" placeholder="Ara..." value="<?php echo get("q") ?>" class="form-control" />
		</form>
	</div>
</div>
<br /><?php /**PATH /home/truncgil/happyworks.truncgil.link/resources/views/admin/inc/table-search.blade.php ENDPATH**/ ?>