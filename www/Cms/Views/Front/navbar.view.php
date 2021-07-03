<nav>
    <h2><?=$site->getName()?></h2>
    <?php if(!empty($pagesToShow) && count($pagesToShow) > 0): ?>
        <?php foreach($pagesToShow as $page): ?>
            <li><a href="<?=DOMAIN . '/site/' . $site->getSubDomain() . '/' . $page['name']  ?>" >
            <?= $page['name'] ?></a></li>
        <?php endforeach;?>
    <?php endif; ?>
</nav>