<div class="main-container">
    <div class="col-8 dishes-container">
        <?php if(isset($categories) && $categories && count($categories) > 0): ?>
            <?php foreach($categories as $category): ?>
                <h1>Dishes</h1>
                <hr/>
                <?php if(count($category['dishes']) > 0): ?>
                        <div class="dish-row">
                            <?php foreach($category['dishes'] as $dish): ?>
                                <div class="dish col-3 col-sm-12">
                                    <a href="ent/dish?id=<?= $dish['id'] ?>" ><img alt="Dish" src='<?=  DOMAIN . '/' . $dish['image'] ?>'/></a>
                                    <span class="dish-name"><?= $dish['name'] ?></span>
                                    <span class="dish-category"><?= $dish['category']['name'] ?></span>
                                    <span class="dish-price"><?= $dish['price'] ?>$</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                <?php endif; ?>
            <?php endforeach;?>
        <?php endif; ?>
    </div>
</div>