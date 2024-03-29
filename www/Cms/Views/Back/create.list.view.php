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
    <div class="row" style="justify-content: center;">
        <table id="data" class="display" width="100%"></table>   
    </div>

<script>
    var dataSet = [
        <?php foreach($datas as $data):?>
            [ <?= \App\Core\Helpers::sanitizeList($data); ?> ],
        <?php endforeach;?>
        ];
    
    $(document).ready(function() {
        $('#data').DataTable( {
            data: dataSet,
            columns: [
                <?php foreach($fields as $field):?>
                { title: "<?=$field?>" },
                <?php endforeach;?>
            ]
        } );
    } );
</script>