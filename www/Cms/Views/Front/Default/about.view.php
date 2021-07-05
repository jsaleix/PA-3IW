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

<section id="about-page-section">
    <div id="site-banner"> 
    </div>
    <div id="site-content">
        <h1><?= $site['name']?></h1>
        <p><?= $site['description']?></p>
        <p>Type of food: <?= $site['type'] ?></p>
        <div>
            <p>Created by <?= $site['creator'] ?></p>
        </div>
    </div>
    
</section>

<style>
    #site-banner{
        width: 100vw;
        height: 40vh;
        background-repeat: no-repeat;
        background-image: url("<?=  $imgLink ?>");
        background-size: cover;
    }

    #site-content{
        /*margin-top: -10vh;*/
    }
</style>