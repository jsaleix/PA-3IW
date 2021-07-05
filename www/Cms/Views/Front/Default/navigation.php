
<nav class="default-navbar">
    <h2><?=$site->getName()?></h2>
    
    <div class="slash-container">
        <div class="slash-content">
            <div class="slash"></div>
            <div class="slash"></div>
            <div class="slash"></div>
        </div>
    </div>

    <ul>
        <?php if(!empty($pagesToShow) && count($pagesToShow) > 0): ?>
            <?php foreach($pagesToShow as $page): ?>
                <li><a href="<?=DOMAIN . '/site/' . $site->getSubDomain() . '/' . $page['name']  ?>" >
                <?= $page['name'] ?></a></li>
            <?php endforeach;?>
        <?php endif; ?>
    </ul>

    <div class="mobile-burger" id="openNav">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
    </div>
</nav>

<div class="mobile-nav" id="mobileNav">
    <ul>
        <?php if(!empty($pagesToShow) && count($pagesToShow) > 0): ?>
            <?php foreach($pagesToShow as $page): ?>
                <li><a href="<?=DOMAIN . '/site/' . $site->getSubDomain() . '/' . $page['name']  ?>" >
                <?= $page['name'] ?></a></li>
            <?php endforeach;?>
        <?php endif; ?>
    </ul>
</div>