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
		<meta charset="UTF-8">
		<title>Créer mon site - EasyMeal</title>
		<meta name="Création d'un site" content="Page d'initialisation d'un nouveau site sur EasyMeal">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href=<?php echo STYLES ?>>
		<link rel="icon" href="/Assets/images/logo.png" />
</head>
<body>
	<?=$navbar??''?>

	<div class="container">
		<?php include $this->view ;?>
	</div>
	</main>
</body>
<script src="/Assets/js/backcms.js"></script>

</html>