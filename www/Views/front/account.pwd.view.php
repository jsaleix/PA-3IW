<nav class="homepage-nav" style="background-color: #2DC091;">
    <ul>
        <li><a href="/">HOME</a></li>
        <li><a href="/account/sites">MY SITES</a></li>
        <li><a href="/logout">LOGOUT</a></li>
    </ul>
    <img alt="logo" src="/Assets/images/logo-easymeal.png"/>
</nav>

<div class="sites-container">
    <h2>Change your password</h2>

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
</div>
<?= $alert??''; ?>

<style>
    .input-banner-container{
        display: flex;
        flex-direction: column;
    }

    .input-banner-container > img{
        width: 30%;
    }
</style>