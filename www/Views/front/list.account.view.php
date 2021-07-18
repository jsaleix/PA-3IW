<nav class="homepage-nav" style="background-color: #2DC091;">
    <ul>
        <li><a href="/">HOME</a></li>
        <li><a href="/account">MY ACCOUNT</a></li>
        <li><a href="/logout">LOGOUT</a></li>
    </ul>
    <img alt="logo" src="/Assets/images/logo-easymeal.png"/>
</nav>

<div class="sites-container">
    <?php foreach($lists as $list): ?>
        <h2><?=$list['title']?></h2>
        <hr/>
        <div class="row">
            <?php foreach($list['datas'] as $data):?>
                <div class="col-3">
                    <div class="site">
                        <h1><?= $data['name'] ?></h1>
                        <p><?= $data['subDomain'] ?>.easymeal.cooking</p>
                        <?php if(gettype($data['creator']) == 'array'): ?>
                            <p>Owner: <a href="/profile?id=<?=$data['creator']['id']?>"><?= $data['creator']['firstname'].' '. $data['creator']['lastname']?></a></p>
                        <?php endif;?>
                        <p>Creation date: <?= $data['creationDate'] ?></p>
                        <div class="row">
                            <a target="_blank" href="<?= DOMAIN . "/site/" . $data['subDomain']?>" class="site-btn">VISIT</a>
                            <a href="<?= DOMAIN . "/site/" . $data['subDomain']."/admin/settings"?>" class="site-btn">EDIT</a>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
            
        </div>

    <?php endforeach;?>
    
</div>


<!-- <div class="row" >
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
</div> -->
<!-- <?php foreach($lists as $list): ?>
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
</script> -->