<!DOCTYPE html>
<html lang="FR">
<head>
	<meta charset="UTF-8">
	<title>Easymeal | Restaurant CMS</title>
	<link rel="icon" href="/Assets/images/logo.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="/Assets/js/jquery-3.5.1.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
	<script src="/Assets/js/backcms.js"></script>
	<link rel="stylesheet" href="<?= DOMAIN."/Cms/Views/Back/Styles/improuvements.css" ?>"/>
	<link rel="stylesheet" href=<?php echo STYLES ?>>

</head>

<body>
	<div class="alert-container" id="alert-container">

	</div>

	<?php include $this->view ;?>

</body>
</html>