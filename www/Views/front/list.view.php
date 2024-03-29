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

    <div class="col-12 col-sm-12 col-md-12 col-xl-12">
            <div class="col-inner">
                <table id="data" class="display" width="100%"></table>
            </div>
        </div>
    </div>
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