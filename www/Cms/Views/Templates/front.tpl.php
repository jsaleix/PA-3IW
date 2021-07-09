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

	<?= \CMS\Core\FooterBuilder::renderFrontFooter($this->site) ?>
</body>
<?= CMS\Core\StyleBuilder::renderStyleScript($this->site) ?>
</html>