<section id="menu-page-section">
    <h2>Menu <?= $menu['name'] ?></h2>
    <?php if(isset($dishes) && $dishes && count($dishes) > 0): ?>
        <?php foreach($dishes as $dish): ?>
            <div class="dish-container">
                <div class="dish-img-div">
                    <img class="dish-picture" src='<?=  DOMAIN . '/' . $dish['image'] ?>'/>
                </div>
                <div class="dish-data">
                    <h2><a href="ent/dish?id=<?= $dish['id']?>"><?= $dish['name'] ?></a></h2>
                    <h3>#<?= $dish['category']?></h3>
                    <p><?=$dish['description']?></p>
                </div>
            </div>
        <?php endforeach;?>
    <?php endif; ?>

</section>

<style>

    .dish-container{
        display: flex;
        flex-direction: 'row';
        box-shadow: 0 6px 6px rgba(0,0,0,0.2);
        padding: 10px;
        width: 80%;
        height: 10em;
    }

    .dish-img-div{
        width: 30%;
    }

    .dish-picture{
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .dish-data{
        padding-left: 10px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        width: 70%;
    }

    .dish-data > * {
        margin: 0;
    }


</style>

