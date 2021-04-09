<!DOCTYPE html>
<html lang="FR">
<head>
	<meta charset="UTF-8">
	<title>Créer mon site - EasyMeal</title>
	<meta name="Création d'un site" content="Page d'initialisation d'un nouveau site sur EasyMeal">
    <link rel="stylesheet" href=<?php echo STYLES ?>>
</head>
<body style="background-color: #2DC091;">
	<!-- intégration de la vue -->
	<?php include $this->view ;?>
</body>

<script>
    function nextStep(){
        let name = document.getElementById ('name').value;
        let description = document.getElementById ('description').value;
        if(name && description){
            siteData = JSON.stringify({ name, description});
            localStorage.setItem('siteData', siteData);
            document.location.href = '?step=3';
        }
    }

    async function createSite(){
        let category    = document.getElementById ('category').value;
        let type        = document.getElementById ('type').value;
        let url         = document.getElementById ('url').value;
        let currentData = localStorage.getItem('siteData');
        currentData = JSON.parse(currentData);
        let { name, description } = currentData;

        let body = {name, description, type, category, url};
        let formBody = [];
        for (let property in body) {
            let encodedKey = encodeURIComponent(property);
            let encodedValue = encodeURIComponent(body[property]);
            formBody.push(encodedKey + "=" + encodedValue);
        }
        formBody = formBody.join("&");

        try{
            let res = await fetch('?step=finalize', 
            {
                method: 'POST',
                headers:{
                    'Content-type': 'application/x-www-form-urlencoded'
                },
                body: formBody
            })
            .then( (res) => {console.log(res); return res})
            .then( (res) => res.status);
            if(res == 201){
                alert('Site crée !');
                //localStorage.removeItem('siteData');
            }else{
                alert('Une erreur est survenue');
            }
        }catch(e){
            console.error(e);
        }
    }
</script>

</html>