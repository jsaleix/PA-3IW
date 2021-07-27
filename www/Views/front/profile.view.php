<nav class="homepage-nav" style="background-color: #2DC091;">
    <ul>
        <li><a href="/">HOME</a></li>
        <li><a href="/account/sites">MY SITES</a></li>
        <li><a href="/logout">LOGOUT</a></li>
    </ul>
    <img alt="logo" src="/Assets/images/logo-easymeal.png"/>
</nav>

<div class="sites-container">

    <?php if(isset($errors)):?>
        <?php foreach ($errors as $error):?>
            <li style="color:red"><?=$error;?></li>
        <?php endforeach;?>
    <?php endif;?>
    <h2><?= $pageTitle ?></h2>
    <div style="display: flex; flex-direction: row; width: 100%;">
        <section>
            <img id="profile_pic" src="<?=DOMAIN . '/'. $user->getAvatar()?>"/>
            <div id="role_div">
                <img id="role_icon" src="<?php echo DOMAIN . '/'. $role->getIcon(); ?>">
                <p><?php echo $role->getName(); ?></p>
            </div>
        </section>

        <section id="sites_section">
            <?php if(isset($sites)): ?>
                <section class="site_list">
                    <?php foreach($sites as $site): ?>
                        <div class="site_row" style="background-image: url('<?= DOMAIN . $site['image']?>'); ">
                            <div class="site_data">
                                <div>
                                    <h2><?=$site['name']?></h2>
                                    <p><?= $site['description'] ?></p>
                                </div>
                                <a class="visit_btn" href="<?= DOMAIN.'/site/'.$site['subDomain']?>">Visit</a>
                            </div>
                            <div style="position: absolute; background-color: black; width: 100%; height: 100%; opacity: 20%;"></div>
                        </div>
                    <?php endforeach; ?>
                </section>
            <?php else: ?>
                <p>This user owns no site yet </p>
            <?php endif; ?>
        </section>
    </div>
    
    <hr>
</div>

<style>
    #profile_pic{
        display: block;
        width:300px;
        height:330px;
        object-fit: cover;
    }

    #role_div{
        display: flex;
        flex-direction: row;
        align-items: center;
    }

    #role_icon{
        width: 60px;
        height: 60px;
        object-fit: cover;
    }

    #sites_section{
        width: 100%;
        display: flex;
        flex-direction: row;
        padding-left: 10px;
        margin-top: 0;
    }

    .site_list{
        width: 100%;
    }

    .site_row{
        width: 100%;
        background-size: cover;
        background-color: gray;
        position: relative;
        margin-bottom: 10px;
        border-radius: 5px;
        overflow: hidden;
    }

    .site_data{
        padding: 10px;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .site_data *{
        padding: 0 !important;
        margin: 0 !important;
        margin-right: 5px;
    }

    .site_data p{
        color: white;
    }

    .site_data h2{
        color: white;
    }


    .visit_btn{
        text-decoration: none;
        background-color: #2DC091;
        justify-content: center;
        padding: 0.5em 2.2em !important;
        color: white;
        font-weight: bold;
        font-size: 12px;
        border: none;
        margin: 0.1em;
        transition: 0.5s;
        text-decoration: none;
    }

</style>