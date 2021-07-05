<nav>
    <h2><?=$site->getName()?></h2>
    <ul>
        <?php if(!empty($pagesToShow) && count($pagesToShow) > 0): ?>
            <?php foreach($pagesToShow as $page): ?>
                <li><a href="<?=DOMAIN . '/site/' . $site->getSubDomain() . '/' . $page['name']  ?>" >
                <?= $page['name'] ?></a></li>
            <?php endforeach;?>
        <?php endif; ?>
    </ul>
</nav>
<style>
    nav{
        display: flex;
        flex-direction: 'row';
        align-items: center;
        margin: 0;
        padding: 0;
    }
</style>