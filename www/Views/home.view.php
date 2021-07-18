<h1>Easy Meal</h1>
<hr>
<section>
	<?php if(isset($connected) && $connected):?>
		<h2>Welcome <?= $pseudo;?></h2>
		<a href="logout">Logout</a>
		<a href="account">My account</a>
		<a href="account/sites">My sites</a>
		<?php if(isset($isAdmin) && $isAdmin):?>
		<a href="admin">admin dashboard</a>
		<?php endif;?>
	<?php else: ?>
		<a href="login">Login</a>
		<a href="register">Register</a>
	<?php endif;?>
</section>


