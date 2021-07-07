<?php
    if(isset($site['image']) && !empty($site['image'])){
        if(strpos($site['image'], 'http') === false){
            $imgLink = DOMAIN . '/' . $site['image'];
        }else{
            $imgLink = $site['image'];
        }
    }else{
        $imgLink = 'https://images.unsplash.com/photo-1498837167922-ddd27525d352?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80';
    }
?>

<main class="main-container">
    <div class="row" style="width:100%;">
        <div class="col-6 col-sm-12 info-left">
            <img src="<?= $imgLink ?>"/>
        </div>
        <div class="col-6 col-sm-12 info-right">
            <h1><?= $site['name']?></h1>
            <p class="info-type"><?= $site['type'] ?></p>
            <p class="info-description"><?= $site['description']?></p>
            <br/>
            <h2>Contact Us</h2>
            <div class="infos col-9 col-sm-12">
                <ul>
                    <li><img src="<?= DOMAIN . '/Assets/images/icons/phone.png'?>"/> 01 93 32 64 18</li>
                    <li><img src="<?= DOMAIN . '/Assets/images/icons/mail.png'?>"/> <a href="mailto:contact@legorille.fr">contact@legorille.fr</a></li>
                    <li><img src="<?= DOMAIN . '/Assets/images/icons/map-pin.png'?>"/> <a title="Open in google maps" href="#map">3 Rue de la piraterie, Tortuga, Océan Pacifique</a></li>
                </ul>
            </div>
        </div>
    </div>
   </main>