<img id="dishImg" src="<?=DOMAIN . '/' . $dish['image'] ?>"/>
<h4><?=$dish['name']?></h4>
<p><?=$dish['description']?></p>
<p><?=$dish['notes']?></p>
<p><?=$dish['allergens']?></p>
<p>$<?=$dish['price']?></p>

<style>
#dishImg{
    width: 30%;
}
</style>