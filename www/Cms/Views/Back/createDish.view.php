<form method="POST" id="form_content" class="form-content" action="" enctype="multipart/form-data">
        
    <div class="col-12 col-sm-12 col-md-12">
        <div class="pageTitle">
            <h2 style="font-weight: lighter;"><?=$pageTitle?></h2>
        </div>
    </div>

    <?php if(isset($errors)):?>
        <?php foreach ($errors as $error):?>
            <?= App\Core\Helpers::displayAlert("error",$error,2500) ?>
        <?php endforeach;?>
    <?php endif;?>

    <div class="row" style="justify-content: space-between;">
        <div class="col-4 col-sm-12 col-md-12">
            <div class='input-banner-container'>
                <?php if(isset($image)):?>
                    <img src="<?=$image?>"/>
                    <label for="dish-select-image">Change image</label>
                <?php else:?>
                    <img src="<?= DOMAIN."/Assets/images/dish-select.png" ?>" style="width: 100%;" />
                    <label for="dish-select-image">Set Dish Image</label>
                <?php endif;?>
                <input id="dish-select-image" name="image" type="file" class="cta-green"/>
            </div>
            <br/>
            <input autocomplete="off" name="allergens" id="remarksInput" class="input input-100" type="text" placeholder="Allergènes" value="<?=$allergens??''?>"/>
            
        </div>
        <div class="col-7 col-sm-12 col-md-12">
            <input class="input input-100" name="name" type="text" placeholder="Name" value="<?=$name??''?>"/>
            <input  name="description" class="input input-100" type="text" placeholder="Description" value="<?=$description??''?>"/>
            <div class="alignedInputs">
                <input class="input" name="price" type="number" placeholder="Price" step="0.1" value="<?=$price?>"/>
                <select class="input" name="category">
                    <option value="0">None</option>
                        <?php foreach ($categories as $key => $value):?>
                            <?php if(isset($category)):?>
                                <option value='<?=$key?>' <?= $key == $category ? 'selected' : '' ?>><?=$value?></option>
                            <?php else: ?>
                                <option value='<?=$key?>'><?=$value?></option>
                            <?php endif; ?>
                        <?php endforeach;?>
                </select>
            </div>
            <input class="input input-100" name="notes" type="text" placeholder="Notes" value="<?=$notes??''?>"/>
            <button type="submit" class="btn btn-light btn-100" ><?= $submitLabel ?></button>
        </div>

    </div>
</form>
<?= $alert??''; ?>
