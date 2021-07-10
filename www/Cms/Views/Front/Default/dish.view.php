<main class="main-container">
    <div class="row dish-container">
        <div class="col-6 col-sm-12">
            <img class="dish-img" alt="Dish image" src="<?=DOMAIN . '/' . $dish['image'] ?>"/>
        </div>
        <div class="col-6 col-sm-12 dish-col">
            <div>
                <div class="row" style="justify-content: flex-end;">
                    <h1><?=preg_replace("/\\\+/", "", $dish['name'])?></h1>
                </div>
                <div class="row" style="justify-content: flex-end; padding-top: 0;">
                    <p><?=$dish['description']?></p>
                </div>
                <hr/>
                <?php if($dish['notes'] != ""):?>
                    <span><b>Composition:</b> <span class="min"><?=$dish['notes']?></span></span>
                <?php endif;?>
                <br/>
                <br/>
                <?php if($dish['allergens'] != ""):?>
                    <span><b>Allergènes:</b> <span class="min"><?=$dish['allergens']?></span></span>
                <?php endif;?>

            </div>
            <h3 class="price"><?=$dish['price']?> $</h3>
        </div>
    </div>

</main>

