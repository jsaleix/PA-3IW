<div class="row" >
    <div class="col-12 col-sm-12 col-md-12 col-xl-12">
        <div class="col-inner">
            <div class="pageTitle">
                <h2 style="font-weight: lighter;"><?=$pageTitle?></h2>
                <br>
                <?php if(isset($createButton)): ?>
                    <a href="<?= $createButton['link']?>"><button class="cta-green"><?=$createButton['label']?></button></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php foreach($lists as $list): ?>
    <h2><?=$list['title']?></h2>

    <div class="row" >
        <div class="col-12 col-sm-12 col-md-12 col-xl-12">
            <div class="col-inner">
                <br>
                <table id=<?=$list['id']?> class="display" width="100%"></table>
            </div>
        </div>
    </div>
<?php endforeach;?>

<script>
    <?php foreach($lists as $list): ?>
        var dataSet_<?=$list['id']?> = [
            <?php foreach($list['datas'] as $data):?>
                    [ <?=$data?> ],
            <?php endforeach;?>
            ];
        
        $(document).ready(function() {
            $('#<?= $list['id']?>').DataTable( {
                data: dataSet_<?=$list['id']?>,
                columns: [
                    <?php foreach($list['fields'] as $field):?>
                    { title: "<?=$field?>" },
                    <?php endforeach;?>
                ]
            } );
        } );
    <?php endforeach; ?>
</script>