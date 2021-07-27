<form method="POST" id="form_content" class="form-content" action="" enctype="multipart/form-data">
        <div class="row" >
            <div class="col-12 col-sm-12 col-md-12 col-xl-12">
                <div class="pageTitle">
                    <h2 style="font-weight: lighter;"><?=$pageTitle?></h2>
                </div>
            </div>
        </div>

        <?php if(isset($errors)):?>

        <?php foreach ($errors as $error):?>
            <li style="color:red"><?=$error;?></li>
        <?php endforeach;?>

        <?php endif;?>

        <div class="row" >
            <div class="col-4 col-sm-12 col-md-12 col-xl-4">
                <?php App\Core\FormBuilder::render($form)?>
                <a style="text-decoration: none;" target="_blank" class="btn btn-100" href="../menus/export?id=<?= $_GET['id'] ?>">Export (HTML)</a>
            </div>

            <div class="col-8 col-sm-12 col-md-12 col-xl-8" style="padding-left:1em; padding-right:1em;">

                <?php if(isset($dishes) && !empty($dishes)): ?>
                    <div class="row">
                        <?php foreach($dishes as $dish): ?>
                            <div class="dish-data no-pointer">
                            <form action="" method="POST">
                                <img src="<?=$dish['image']?>"/>
                                <p><?=$dish['name']?></p>
                                <input type="hidden" name="action" value="remove_dish"/>
                                <input type="hidden" name="dish" value="<?=$dish['id']?>"/>
                                <button class="remove-btn" type="submit" >
                                    <img src="/Assets/images/icons/remove.png"  alt="delete"/>
                                </button>
                            </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="add-product">
                    <h2>Add product in your menu </h2>

                    <p>Product category: </p>
                    <select class="input input-100 input-select" name="category" id="category-selector" onChange="getDishes()">
                        <option value="0">None</option>
                            <?php foreach ($categories as $key => $value):?>
                                <option value='<?=$key?>'><?=$value?></option>
                            <?php endforeach;?>
                    </select>

                    <div id="add-section" class="row">
                        
                    </div>
                </div>

            </div>

            

        </div>
</form>

<script>
    getDishes();

    async function getDishes(){
        eraseDishList();
        let menuId = (new URL(document.location)).searchParams;
        menuId = menuId.get('id');
        if(!menuId) return;
        let select = document.getElementById('category-selector');
        select = select.value;
        try{
            let res = await fetch('<?=DOMAIN?>/site/<?=$subDomain?>/admin/api/searchdish?category=' + select + '&menu=' + menuId, 
            {
                method: 'GET',
                headers:{
                    'Content-type': 'application/x-www-form-urlencoded'
                },
            })
            .then( (res) => res.json());

            if(res.code === 200){
                res.dishes.forEach((dish) => {
                    displayDish(dish, 'add-section');
                });
            }
        }catch(e){}
    }

    function eraseDishList(){
        document.getElementById('add-section').innerHTML = "";
    }

    function displayDish(dish, id){
        let div = document.getElementById(id);
        let dishDiv = document.createElement('div');
        dishDiv.setAttribute('class', 'dish-data');
        dishDiv.setAttribute('onClick', "addDish(" + dish.id + ")");

        let img = document.createElement('img');
        img.setAttribute('src', dish?.image);
        dishDiv.appendChild(img);

        let name = document.createElement('p');
        name.innerHTML= dish?.name;
        dishDiv.appendChild(name);

        div.appendChild(dishDiv);
    }

    function addDish(id){
        let menuId = (new URL(document.location)).searchParams;
        menuId = menuId.get('id');
        if(!menuId) return;

        let form    = document.createElement("form");
        let dish    = document.createElement("input"); 
        let menu    = document.createElement("input");  
        let action  = document.createElement("input");  

        form.method = "POST";
        form.action = "";   

        action.value = "add_dish";
        action.name = "action";
        form.appendChild(action);  

        dish.value=id;
        dish.name="dish";
        form.appendChild(dish);  

        menu.value=menuId;
        menu.name="menu";
        form.appendChild(menu);

        document.body.appendChild(form);

        form.submit();
    }

    function removeDish(id){

    }
</script>
<?= $alert??''; ?>