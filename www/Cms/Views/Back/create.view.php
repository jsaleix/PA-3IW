    <div class="row" >
        <div class="col-12 col-sm-12 col-md-12 col-xl-12">
            <div class="col-inner">
                <div class="pageTitle">
                    <h2 style="font-weight: lighter;"><?=$pageTitle??''?></h2>
                    <?php if(isset($button)):?>
                        <a href="<?= $button['link']?>"><button class="cta-green"><?=$button['label']?></button></a>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
    
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
    
<style>
    form{
        width: 80%;
        display: flex;
        flex-direction: column;
        flex-direction: flex-start;
    }

    form input{
        margin-bottom: 10px;
        background-color: transparent;
        border: 1px solid #2DC091;
        color: black;
        padding: 0.8em;
        padding-left: 1em;
        padding-right: 1em;
        font-weight: normal;
        outline: none;
        font-size: 16px;
    }
</style>