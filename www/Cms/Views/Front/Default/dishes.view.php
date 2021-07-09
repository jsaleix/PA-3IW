<div class="main-container">
    <div class="col-8 dishes-container">
        <h1>Dishes</h1>
        <hr/>
        <?php if(isset($categories) && $categories && count($categories) > 0): ?>
            <?php foreach($categories as $category): ?>
                <h2><?= $category['category']['name'] ?></h2>
                <hr/>
                <?php if(count($category['dishes']) > 0): ?>
                    <div class="row">
                        <?php foreach($category['dishes'] as $dish): ?>
                            <div class="dish-col col-3 col-sm-12">
                                <div class="dish">
                                    <a href="ent/dish?id=<?= $dish['id'] ?>" ><img alt="Dish" src='<?=  DOMAIN . '/' . $dish['image'] ?>'/></a>
                                    <span class="dish-name"><?= $dish['name'] ?></span>
                                    <span class="dish-price"><?= $dish['price'] ?>$</span>
                                </div>
                            </div>
                            
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach;?>
        <?php endif; ?>
    </div>
</div>