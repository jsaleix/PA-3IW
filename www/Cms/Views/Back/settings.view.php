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
        <div style="display: flex; flex-direction: column;">

        <?php if(isset($deletePage) && $deletePage): ?>
            <p>Be sure this is what you want, <br>this action is irreversible.</p>
            <?php App\Core\FormBuilder::render($form)?>
            <?php else: ?>
                <?php App\Core\FormBuilder::render($form)?>
                <form class="edit-site col-5 col-sm-12">
                    <a class="width-80 " href="<?= \App\Core\Helpers::renderCMSLink( "admin/settings/delete", $this->site) ?> "><input class="cta-blue width-80 last-sm-elem" type="button" value="Delete"></a>
                </form>
            <?php endif; ?>
        </div>
    </div>
    
