<!DOCTYPE html>
<html lang="FR">
<head>
	<meta charset="UTF-8">
	<title><?=$pageTitle?></title>
	<meta name="EasyMeal - Y restaurant's">
    <?= $style??''?>
</head>
<body>
	<header>
		<?=$navbar??''?>
	</header>
	<?php include $this->view ;?>

</body>
</html>