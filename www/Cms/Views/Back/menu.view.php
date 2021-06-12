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
                        <input 
                            name="name" 
                            type="text" 
                            placeholder="Nom" 
                            value="<?=$name??''?>"/>
                        <input 
                            name="description"  
                            class="longInput" 
                            type="text" 
                            placeholder="Description" 
                            value="<?=$description??''?>"/>
                        <input 
                            name="description"  
                            class="longInput" 
                            type="text" 
                            placeholder="Notes" 
                            value="<?=$notes??''?>"/>
                    </div>
                </div>

                
            </div>

            <div class="col-8 col-sm-12 col-md-12 col-xl-8">
                <div class="col-inner">
                    <div class="darkSection">
                        <h2>Add product in your menu </h2>
                        <select name="category" id="category-selector" onChange="getDishes()">
                            <option value="0">None</option>
                                <?php foreach ($categories as $key => $value):?>
                                    <option value='<?=$key?>'><?=$value?></option>
                                <?php endforeach;?>
                        </select>

                        <div id="add-section" class="dish-list"></div>
                    </div>
                </div>

                <?php if(isset($dishes) && !empty($dishes)): ?>
                    <div class="col-inner">
                        <div class="darkSection">
                            <div class="dish-list">
                                <?php foreach($dishes as $dish): ?>
                                    <div class="dish-data">
                                        <img src="<?=$dish['image']?>"/>
                                        <p><?=$dish['name']?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            

        </div>
</form>

<script>
    getDishes();

    async function getDishes(){
        eraseDishList();
        let select = document.getElementById('category-selector');
        select = select.value;
        try{
            let res = await fetch('<?=DOMAIN?>/site/<?=$subDomain?>/admin/searchdish?category=' + select, 
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
</script>

<style>

    .dish-list{
        width: 100%;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
    }

    .dish-data{
        display: flex;
        flex-direction: column;
        justify-content: 'flex-start';
        width: 25%;
        padding: 1%;
        border-radius: 5px;
    }

    .dish-data:hover{
        background-color: white;
        cursor: pointer;
    }

    .dish-data img{
        width: 100%;
    }
</style>
