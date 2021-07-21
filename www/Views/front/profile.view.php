<?php if(isset($errors)):?>
    <?php foreach ($errors as $error):?>
        <li style="color:red"><?=$error;?></li>
    <?php endforeach;?>
<?php endif;?>
<h2><?= $pageTitle ?></h2>
<section>
    <img id="profile_pic" src="<?=DOMAIN . '/'. $user->getAvatar()?>"/>
    <p><?= $user->getFirstname() . ' ' . $user->getLastname() ?>
    <div id="role_div">
        <img src="<?php echo DOMAIN . '/'. $role->getIcon(); ?>">
        <p><?php echo $role->getName(); ?></p>
    </div>
    
</section>

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
    #profile_pic{
        display: block;
        max-width:350px;
        max-height:195px;
        width: auto;
        height: auto;
    }

</style>