
<div class="row" >
<div class="col-12 col-sm-12 col-md-12 col-xl-12">
        <div class="col-inner">
            <div class="pageTitle">
                <h2 style="font-weight: lighter;"><?=$pageTitle?></h2>
                <?php if(isset($createButton)): ?>
                    <a href="<?= $createButton['link']?>"><button class="cta-green"><?=$createButton['label']?></button></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="row" style="justify-content: center;">
    <?php if( !empty( $settings ) && $settings == true ):?>
        <?php App\Core\FormBuilder::render($form)?>  
    <?php endif;?>
    <?php if( !empty( $planning ) && $planning == true ):?>
        <div style="display: flex; flex-direction: column;">
            <p>Planning</p><br>
            <div>
                <?php //if( !empty($forms) ):?>
                    <?php //foreach($forms as $f):?>
                        <?php App\Core\FormBuilder::render($f);?>  
                    <?php //endforeach;?>
                <?php// endif;?>
            </div>
        </div>
    <?php endif;?>
</div>


<style>
    *{
        -webkit-appearance: auto;
        scrollbar-width: auto;
    }
</style>