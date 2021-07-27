<main class="main-container">
    <div class="col-10 article-container">
        <h1>Oops</h1>
        <?php if(isset($errors)):?>

        <?php foreach ($errors as $error):?>
            <li style="color:red"><?=$error;?></li>
        <?php endforeach;?>

        <?php endif;?>

        <?php if(isset($message)):?>
            <h3> <?=$message?> </h3>
        <?php endif;?>
        <div class="row" style="justify-content: center;">
            <h2>Sorry, something went wrong with this page</h2>
        </div>
    </div>
</main>    