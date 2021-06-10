<form method="POST" id="form_content" class="form-content" action="" enctype="multipart/form-data">
        <div class="row" >
            <div class="col-12 col-sm-12 col-md-12 col-xl-12">
                <div class="col-inner">
                    <div class="pageTitle">
                        <h2 style="font-weight: lighter;"><?=$pageTitle?></h2>
                    </div>
                </div>
            </div>
        </div>

        <?php if(isset($errors)):?>

        <?php foreach ($errors as $error):?>
            <li style="color:red"><?=$error;?></li>
        <?php endforeach;?>

        <?php endif;?>

        <?php if(isset($message)):?>
            <h3> <?=$message?> </h3>
        <?php endif;?>


        <div class="row" >
            <div class="col-4 col-sm-12 col-md-12 col-xl-4">
                <div class="col-inner">
                    <div class="darkSection">
                        <?php if(isset($image)):?>
                            <img src="<?=$image?>" style="width: 100%;" />
                            <label>Change image</label>
                        <?php else:?>
                            <label>Set Image</label>
                        <?php endif;?>
                        <input name="image" type="file" class="cta-green"/>
                    </div>
                </div>

                <div class="col-inner">
                    <div class="darkSection">
                        <input name="allergens" id="remarksInput" class="longInput" type="text" placeholder="Allergènes" value="<?=$allergens??''?>"/>
                    </div>
                </div>
            </div>

            <div class="col-8 col-sm-12 col-md-12 col-xl-8">
                <div class="col-inner">
                    <div class="darkSection">
                        <input name="name" type="text" placeholder="Nom" value="<?=$name??''?>"/>
                        <input  name="description"  class="longInput" type="text" placeholder="Description" value="<?=$description??''?>"/>
                        <div class="alignedInputs">
                            <input name="price" type="number" placeholder="Price" step="0.1" value="<?=$price?>"/>
                            <select name="category">
                                <option value="0">None</option>
                                    <?php foreach ($categories as $key => $value):?>
                                        <?php if(isset($category) && count($category) > 0):?>
                                            <option value='<?=$key?>' <?php $key === $category ? 'selected=selected' : '' ?>><?=$value?></option>
                                        <?php else: ?>
                                            <option value='<?=$key?>'><?=$value?></option>
                                        <?php endif; ?>
                                    <?php endforeach;?>
                            </select>
                        </div>
                        <input class="longInput" name="notes" type="text" placeholder="Notes" value="<?=$notes??''?>"/>
                        <div class="validateRow">
                            <button type="submit" class="cta-white" >Ajouter</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
</form>
