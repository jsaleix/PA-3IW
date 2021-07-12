<main class="main-container">
    <div class="col-10 article-container">
        <h1><?=$pageTitle?></h1>
        <?php if(isset($errors)):?>

        <?php foreach ($errors as $error):?>
            <li style="color:red"><?=$error;?></li>
        <?php endforeach;?>

        <?php endif;?>

        <?php if(isset($message)):?>
            <h3> <?=$message?> </h3>
        <?php endif;?>
        <div class="row" style="justify-content: center;">
            <?php App\Core\FormBuilder::render($form)?>    
        </div>
    </div>
</main>    
    
<script>
 alert("ceci est un script");
</script>