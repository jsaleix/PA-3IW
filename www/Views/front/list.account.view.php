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
        <?php if(isset($list['datas']) && count($list['datas']) > 0): ?>
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
            <?php endif; ?>
            
        </div>

    <?php endforeach;?>
    
</div>

