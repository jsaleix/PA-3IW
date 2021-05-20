<?php if(isset($errors)):?>

<?php foreach ($errors as $error):?>
	<li style="color:red"><?=$error;?></li>
<?php endforeach;?>

<?php endif;?>

<main class="main-container">
    <?php App\Core\FormBuilder::render($form)?>
</main>