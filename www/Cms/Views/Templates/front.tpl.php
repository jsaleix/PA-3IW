<!DOCTYPE html>
<html lang="FR">
<head>
	<meta charset="UTF-8">
	<title><?=$pageTitle?></title>
	<meta name="EasyMeal - Y restaurant's">
	<link rel="sitemap" type="application/xml" title="Sitemap" href="<?= \App\Core\Helpers::renderCMSLink( "ent/sitemap.xml", $this->site) ?>">
    <?= CMS\Core\StyleBuilder::renderStyle($this->site) ?>
	<style>
		<?= CMS\Core\StyleBuilder::renderPersonnalStyles($this->site) ?>
	</style>
</head>
<body>
	<?= CMS\Core\navbarBuilder::renderFrontNavigation($this->site) ?>

	<?php include $this->view ;?>

	<?= \CMS\Core\FooterBuilder::renderFrontFooter($this->site) ?>
</body>
<?= CMS\Core\StyleBuilder::renderStyleScript($this->site) ?>
</html>