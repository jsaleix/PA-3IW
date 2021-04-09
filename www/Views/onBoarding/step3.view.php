<h2>Dernière étape</h2>

<?php if(isset($errors)):?>

<?php foreach ($errors as $error):?>
	<li style="color:red"><?=$error;?></li>
<?php endforeach;?>

<?php endif;?>

<h3>Indiquez ici un ou plusieurs type de nourriture que vous vendez</h3>
<input id="category" placeholder="(Italien, thailandais, espagnol etc...)">

<h3>De quel type de restaurant s'agit-il ?</h3>
<input id="type" placeholder="Gastronomique, brasserie gourmet, fast-food ?">

<h3>Enfin choisissez ici le nom qui apparaitra dans l'URL de votre site </h3>
<input id="url" placeholder="votresite">

<a href="?step2">Retour</a>
<a id="endButton" onClick="createSite()">Terminer</a>