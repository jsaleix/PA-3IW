<?php if(isset($errors)):?>

<?php foreach ($errors as $error):?>
	<li style="color:red"><?=$error;?></li>
<?php endforeach;?>

<?php endif;?>


<main class="main-container">
            <div class="head-row row">
                <div class="col-6 col-md-12 col-sm-12 head-sm">
                    <img src="Assets/images/logo.png">
                    <button onclick="location.href='./register'" class="cta-white" type="button">Inscription</button>
                </div>
            </div>
			<div class="logo fadeInRight fadeInRight-s2">
                    <img src="/Assets/images/logoName.png">
                </div>
                <div class="auth">
                    <div class="auth-container fadeInLeft fadeInLeft-s2">
					
						<?php App\Core\FormBuilder::render($form)?>
						                          
                        <div class="form-auth" id="second-auth" >
                            <hr>
                            <h3 style="font-weight: lighter;">Deja un compte  ?</h3>
                            <button onclick="location.href='./login'" class="cta-white width-80 last-elem" type="button" >Connexion</button>
                        </div>
                    </div>
                </div>
        </main>