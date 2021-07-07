<!DOCTYPE html>
<html lang="FR">
<head>
	<meta charset="UTF-8">
	<meta name="EasyMeal - restaurant's backoffice">
	<meta charset="UTF-8">
	<title>Manage my site - EasyMeal</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="/Assets/images/logo.png" />
	<script src="/Assets/js/jquery-3.5.1.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
	<link rel="stylesheet" href="<?= DOMAIN."/Cms/Views/Back/Styles/main.css" ?>"/>
	<link rel="stylesheet" href="<?= DOMAIN."/Cms/Views/Back/Styles/improuvements.css" ?>"/>
</head>
<body>
	<div class="pageContainer">
		<div class="navbarTop">
			<div class="topHead">
				<button class="menuBtn" id="openBtn">
					<svg viewBox="0 0 22 15" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M1.25 15H15C15.6875 15 16.25 14.4375 16.25 13.75C16.25 13.0625 15.6875 12.5 15 12.5H1.25C0.5625 12.5 0 13.0625 0 13.75C0 14.4375 0.5625 15 1.25 15ZM1.25 8.75H11.25C11.9375 8.75 12.5 8.1875 12.5 7.5C12.5 6.8125 11.9375 6.25 11.25 6.25H1.25C0.5625 6.25 0 6.8125 0 7.5C0 8.1875 0.5625 8.75 1.25 8.75ZM0 1.25C0 1.9375 0.5625 2.5 1.25 2.5H15C15.6875 2.5 16.25 1.9375 16.25 1.25C16.25 0.5625 15.6875 0 15 0H1.25C0.5625 0 0 0.5625 0 1.25ZM21.625 11.1L18.025 7.5L21.625 3.9C22.1125 3.4125 22.1125 2.625 21.625 2.1375C21.1375 1.65 20.35 1.65 19.8625 2.1375L15.375 6.625C14.8875 7.1125 14.8875 7.9 15.375 8.3875L19.8625 12.875C20.35 13.3625 21.1375 13.3625 21.625 12.875C22.1 12.3875 22.1125 11.5875 21.625 11.1Z" fill="#9E2DC0"/>
					</svg>
				</button>
				<img class="logoHead" src="/Assets/images/logo_black.png"/>
			</div>
			<div class="nameMenu">
				<p>John Doe</p>
				<img class="avatar" src="https://png.pngtree.com/png-vector/20190710/ourlarge/pngtree-user-vector-avatar-png-image_1541962.jpg"/>
			</div>
		</div>
		<main>
			<?= CMS\Core\navbarBuilder::renderBackNavigation($this->site) ?>

			<div class="container">
				<?php include $this->view ;?>
			</div>
		</main>
	</div> 
</body>
<script src="/Assets/js/backcms.js"></script>
</html>