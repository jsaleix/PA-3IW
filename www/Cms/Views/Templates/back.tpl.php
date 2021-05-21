<!DOCTYPE html>
<html lang="FR">
<head>
	<meta charset="UTF-8">
	<title>BACKOFFICE</title>
	<meta name="EasyMeal - restaurant's backoffice">
	<style>
	.inline-list{
		display: flex;
		flex-direction: row;
		justify-content: flex-start;
		align-items: center;
		align-content: center;
	}

	.inline-list > * {
		margin-right: 10px;
	}

	.inline-list > input[type=submit]{
		height: 2em;
	}
	</style>
</head>
<body>
	<header>
		<?=$navbar??''?>
	</header>
	<h1><?= $pageTitle?></h1>
	<?php include $this->view ;?>

</body>
</html>