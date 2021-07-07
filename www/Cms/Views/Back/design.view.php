
<div class="col-10">

    <h1><?= $title ?></h1>
    <hr/>
    <h2>Current theme inuse: <span style="color:#2DC091"><?= $site['theme']; ?></span></h2>

    <h3>Change theme to: </h3>
    <?php App\Core\FormBuilder::render($form)?>    
</div>