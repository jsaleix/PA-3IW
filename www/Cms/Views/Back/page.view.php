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
            <div class="col-12 col-sm-12 col-md-12 col-xl-12">
                <div class="col-inner">
                    <div class="darkSection">
                        <?php App\Core\FormBuilder::render($form)?>
                    </div>
                </div>

            </div>

        </div>

<script>
//form id:           #form_content
//filter input id:   #filters

getFilters();

var selector = document.getElementById('action');
selector.addEventListener("change", async function() {
    if(selector.value !== undefined){
        await getFilters(selector.value);
    }
});

//remove the name from the original filters input
function hideInput(){
    let domInput = document.getElementById('filters');
    if(domInput){
        domInput.setAttribute('name', domInput.getAttribute('name') + '_hidden');
    }
    let filterSelectInput = document.getElementById('filterSelector');
    if(filterSelectInput && filterSelectInput !== undefined){
        filterSelectInput.setAttribute('name', 'filters');
    }
}

//gives back the right name to the original filters input
function showInput(){
    let domInput = document.getElementById('filters');
    if(domInput){
        domInput.setAttribute('name', 'filters');
    }
    let filterSelectInput = document.getElementById('filterSelector');

    if(filterSelectInput && filterSelectInput !== undefined){
        filterSelectInput.setAttribute('name', 'filters_select');
    }
}

async function getFilters(value){
    if(!value){
        let currAction = document.getElementById('action').value;
        value = currAction;
    }
    try{
        let res = await fetch('<?=DOMAIN?>/site/<?=$subDomain?>/admin/api/getFilters?id=' + value, 
        {
            method: 'GET',
            headers:{
                'Content-type': 'application/x-www-form-urlencoded'
            },
        })
        .then( (res) => res.json())
        eraseSelector();

        if(res.code === 200){
            if(res.results.length > 0){
                createSelector();
                res.results.forEach((item) => {
                    addItem(item);
                });
            }else{
                throw new Error('No filters');
            }
        }else{
            throw new Error('Wrong response code');
        }
    }catch(e){
        console.error(e);
        eraseSelector();
    }
}


function createSelector(){
    hideInput();
    let selector = document.getElementById('filterSelector');
    if(selector) return;
    selector = document.createElement('select');
    selector.setAttribute('id', 'filterSelector');
    selector.setAttribute('name', 'filters');

    let form = document.getElementById('form_content');
    form.insertBefore(selector, form.childNodes[form.length]);
}

function eraseSelector(){
    showInput();
    let selector = document.getElementById('filterSelector');
    if(selector){
        selector.remove();
    }
}

function addItem(item){
    let option = document.createElement('option');
    option.setAttribute('value', item['id']);
    option.innerHTML = item['name'];

    let selector = document.getElementById('filterSelector');
    selector.append(option);
}

</script>

<style>
    .radio{
        display: flex;
        flex-direction: row;
        justify-content: space-around;
    }

    /*.radio-option{
        display: flex;
        flex-direction: row;
        justify-content: center;
    }*/

    input[type="radio"]{
        -webkit-appearance: auto !important;
    }
</style>