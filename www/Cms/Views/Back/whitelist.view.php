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

var input = document.getElementById('user');
if(input){ 
    input.setAttribute('type', 'text');
    input.setAttribute('name', 'username-visible');
}
createResultsSection();
createRealInput();

input.addEventListener("change", async function() {
    if(input.value !== undefined){
        await getUsers(input.value);
    }
});


async function getUsers(value){
    eraseResults();
    if(!value){
        return;
    }
    try{
        let res = await fetch('<?=DOMAIN?>/api/users?param=' + value, 
        {
            method: 'GET',
            headers:{
                'Content-type': 'application/x-www-form-urlencoded'
            },
        })
        .then( (res) => res.json())
        .catch( err => eraseSelector());
        if(res.code === 200){
            if(res.users.length > 0){
                res.users.forEach((item) => {
                    addItem(item);
                });
            }else{
                throw new Error('No user');
            }
        }else{
            throw new Error('Wrong response code');
        }
    }catch(e){
        console.error(e);
    }
}


function createResultsSection(){


    resultsSection = document.createElement('div');
    resultsSection.setAttribute('id', 'results' );

    let form = document.getElementById('form_add_user');

    let formBtn = form.lastChild;
    formBtn.setAttribute('type', 'hidden');

    form.append(resultsSection);
}

function eraseResults(){
    let results = document.getElementById('results');
    if(results){
        results.innerHTML = '';
    }
}

function createRealInput(){
    let realInput = document.createElement('input');
    realInput.setAttribute('name', 'user');
    realInput.setAttribute('type', 'hidden');
    realInput.setAttribute('id', 'r_input');

    let form = document.getElementById('form_add_user');
    form.append(realInput);
}

function selectUser(user){
    let realInput = document.getElementById('r_input');
    realInput.value = user;
    let form = document.getElementById('form_add_user');
    form.submit();
}

function addItem(item){
    let resultDiv = document.createElement('button');
    resultDiv.setAttribute('id', item['id']);
    resultDiv.setAttribute('type', 'button');
    resultDiv.setAttribute('onClick', 'selectUser(' + item['id'] + ')');

    let resultP = document.createElement('p');
    resultP.innerHTML = item['name'] + ' | Allow to manage your site';

    resultDiv.append(resultP);

    let results = document.getElementById('results');
    results.append(resultDiv);
}

</script>