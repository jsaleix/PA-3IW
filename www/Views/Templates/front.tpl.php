<!DOCTYPE html>
<html lang="FR">
<head>
	<meta charset="UTF-8">
	<title>Template de FRONT</title>
	<meta name="description" content="ceci est la description de ma page">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href=<?php echo STYLES ?>>
    <link rel="icon" href="/Assets/images/logo.png" />
</head>
<body>

	<!-- intégration de la vue -->
	<?php include $this->view ;?>

</body>
</html>