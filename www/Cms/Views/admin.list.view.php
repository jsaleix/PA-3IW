<?php if(isset($errors)):?>

<?php foreach ($errors as $error):?>
	<li style="color:red"><?=$error;?></li>
<?php endforeach;?>

<?php endif;?>

<?php if(isset($message)):?>
    <h3> <?=$message?> </h3>
<?php endif;?>

    <div class="row" >
        <div class="col-12 col-sm-12 col-md-12 col-xl-12">
            <div class="col-inner">
                <div class="pageTitle">
                    <h2 style="font-weight: lighter;"><?=$pageTitle??''?></h2>
                    <?php if(isset($button)):?>
                        <a href="<?= $button['link']?>"><button class="cta-green"><?=$button['label']?></button></a>
                    <?php endif;?>
                    <!--<button class="cta-green">Ajouter un nouveau fichier</button>-->

                </div>
            </div>
        </div>
    </div>
    
    <?php if(!empty($content)){ echo $content;}?>
    <?php if(!empty($list)){ App\Core\ListBuilder::render($list); }?>
    <!---- END VIEW ------->