<?php if(isset($errors)):?>

<?php foreach ($errors as $error):?>
	<li style="color:red"><?=$error;?></li>
<?php endforeach;?>

<?php endif;?>
<h3>Default admin action on CMS</h3>
<p>We're gonna assume that you are the site owner</p>
<ul>
	<li><a href="/admin/users">Users</a></li>
	<li><a href="/admin/sites">Sites</a></li>
	<li><a href="/admin/roles">Roles</a></li>
</ul>