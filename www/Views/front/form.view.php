<?php if(isset($errors)):?>

<?php foreach ($errors as $error):?>
	<li style="color:red"><?=$error;?></li>
<?php endforeach;?>

<?php endif;?>

<?= isset($content) && $content ?>

<?php if(isset($form)): ?>
    <?php App\Core\FormBuilder::render($form)?>
<?php endif;?>

<?php if(isset($list)): ?>
    <h2>Sites created </h2>
    <table id="data" class="display" width="100%"></table>

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
<?php endif; ?>
