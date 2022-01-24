<nav class="homepage-nav" style="background-color: #2DC091;">
    <ul>
        <li><a href="/">HOME</a></li>
        <li><a href="/account/sites">MY SITES</a></li>
        <li><a href="/logout">LOGOUT</a></li>
    </ul>
    <img alt="logo" src="/Assets/images/logo-easymeal.png"/>
</nav>

<div class="sites-container">
    <h2>Manage my account</h2>
    <hr/>
    <div style="display: flex; flex-direction: column; align-items: center;">
        <?php if(isset($form)): ?>
            <?php App\Core\FormBuilder::render($form)?>
        <?php endif;?>
        <a class="btn btn-light col-4" href="/account/password">Change your password</a>
    </div>
</div>

<?php if(isset($errors)):?>
    <?php foreach ($errors as $error):?>
        <li style="color:red"><?=$error;?></li>
    <?php endforeach;?>
<?php endif;?>

<?= $alert??''; ?>

<!-- <?php if(isset($message)):?>
    <p><?=$message?></p>
<?php endif;?> -->