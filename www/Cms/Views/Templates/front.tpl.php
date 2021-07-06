<!DOCTYPE html>
<html lang="FR">
<head>
	<meta charset="UTF-8">
	<title><?=$pageTitle?></title>
	<meta name="EasyMeal - Y restaurant's">
    <?= CMS\Core\StyleBuilder::renderStyle($this->site) ?>
	
</head>
<body>
	<?= CMS\Core\navbarBuilder::renderFrontNavigation($this->site) ?>

	<?php include $this->view ;?>

	<footer>
        <div class="col-4">
            <h2><?= $this->site->getName(); ?></h2>
            <ul>
                <li><a href="#">Links</a></li>
                <li><a href="#">Links</a></li>
                <li><a href="#">Links</a></li>
                <li><a href="#">Links</a></li>
                
            </ul>
    
            <br/>
            <p><?= $this->site->getName(); ?> Copyright © 2021-2022</p>
        </div>
        <div class="row">
                <div class="row" style="height: 100%;">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
                <div class="row social-container">
                    <button class="social-btn"><img src=<?= DOMAIN."/Assets/images/icons/facebook.png" ?> alt="Facebook" /></button>
                    <button class="social-btn"><img src=<?= DOMAIN."/Assets/images/icons/instagram.png"?> alt="Instagram" /></button>
                    <button class="social-btn"><img src=<?= DOMAIN."/Assets/images/icons/twitter.png" ?> alt="Twitter" /></button>
                </div>
        </div>
    </footer>
</body>
<?= CMS\Core\StyleBuilder::renderStyleScript($this->site) ?>
</html>