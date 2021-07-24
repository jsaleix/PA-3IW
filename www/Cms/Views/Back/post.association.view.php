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
    <div class="row" style="margin-left: 20px;">
        <?php App\Core\FormBuilder::render($form)?>    
    </div>

    <?php if(isset($lists) && !empty($lists)): ?>
        <?php foreach($lists as $list): ?>
            <h2><?=$list['title']?></h2>
            <table id=<?=$list['id']?> class="display" width="100%"></table>
        <?php endforeach;?>
    <?php endif;?>

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

<style>
    #form_content{
        width: 80%;
        display: flex;
        flex-direction: column;
        flex-direction: flex-start;
    }

    #form_content input{
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


    #form_content input[type='textarea']{
        padding-bottom: 10%;
    }

    #form_content input[type='radio']{
        -webkit-appearance: auto;
    }
</style>