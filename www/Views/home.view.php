<section class="homepage">
	<div class="homepage-overlay">
		<nav class="homepage-nav">
			<ul>
				<?php if(isset($connected) && $connected):?>
					<li><a href="account">MY ACCOUNT</a></li>
					<li><a href="account/sites">MY SITES</a></li>
					<?php if(isset($isAdmin) && $isAdmin):?>
						<li><a href="admin">ADMIN</a></li>
					<?php endif;?>
					<li><a href="login">LOGOUT</a></li>
				<?php else: ?>
					<li><a href="login">LOGIN</a></li>
					<li><a href="register">REGISTER</a></li>
				<?php endif;?>
			</ul>
			<img alt="logo" src="/Assets/images/logo-easymeal.png"/>
		</nav>
		<div class="mid-container">
			<div class="titles">
				<h1>THE PERFECT CMS FOR YOUR <br/>RESTAURANT</h1>
				<h2>EASY, FAST & COMPLETE </h2>
				<?php if(isset($connected) && $connected):?>
					<a class="button-overlay" href="account/sites">MY SITES</a>
				<?php else: ?>
					<a class="button-overlay" href="register">REGISTER NOW</a>
				<?php endif;?>
			</div>
		</div>
	</div>

	<img class="bg-img" alt="Background" src="/Assets/images/resto.jpeg"/>
</section>


