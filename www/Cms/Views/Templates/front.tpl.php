<!DOCTYPE html>
<html lang="FR">
<head>
	<meta charset="UTF-8">
	<title><?=$pageTitle?></title>
	<meta name="EasyMeal - Y restaurant's">
    <?= CMS\Core\StyleBuilder::renderStyle($this->site) ?>
	
</head>
<body>
	<header>
		<?= CMS\Core\navbarBuilder::renderFrontNavigation($this->site) ?>
	</header>
	<?php include $this->view ;?>

</body>
<?= CMS\Core\StyleBuilder::renderStyleScript($this->site) ?>
</html>