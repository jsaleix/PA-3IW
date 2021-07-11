<h2>Manage your account</h2>

<?php if(isset($errors)):?>
    <?php foreach ($errors as $error):?>
        <li style="color:red"><?=$error;?></li>
    <?php endforeach;?>
<?php endif;?>

<?php if(isset($message)):?>
    <p><?=$message?></p>
<?php endif;?>

<section>
    <div style="margin-bottom: 5px;">
        <?php if(isset($form)): ?>
            <?php App\Core\FormBuilder::render($form)?>
        <?php endif;?>
    </div>
    <a href="/account"><button  class="cta-blue width-80 last-sm-elem">Manage your account</button></a>

</section>

<style>
    .input-banner-container{
        display: flex;
        flex-direction: column;
    }

    .input-banner-container > img{
        width: 30%;
    }
</style>