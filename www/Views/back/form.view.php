<div class="row" >
    <div class="col-12 col-sm-12 col-md-12 col-xl-12">
        <div class="col-inner">
            <div class="pageTitle" style="width: 80%; display: flex; flex-direction: row; align-items: center; justify-content: space-between">
                <h2 style="font-weight: lighter;"><?= $pageTitle?></h2>
            </div>
        </div>
    </div>
</div>
<hr>

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
                        [ <?=$data?> ],
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

    <style>
        img{
            display: block;
            max-width:350px;
            max-height:195px;
            width: auto;
            height: auto;
        }

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
