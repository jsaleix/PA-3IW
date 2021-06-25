<h1>Easy Meal</h1>
<hr>
<section>
	<?php if(isset($connected) && $connected):?>
		<h2>Welcome <?= $pseudo;?></h2>
		<a href="logout">Se deconnecter</a>
	<?php else: ?>
		<a href="login">Se connecter</a>
	<?php endif;?>
</section>


