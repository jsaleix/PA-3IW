<main class="main-container">
    <div class="col-10 article-container">
        <h1><?=$pageTitle?></h1>
        <?php if(isset($errors)):?>

        <?php foreach ($errors as $error):?>
            <li style="color:red"><?=$error;?></li>
        <?php endforeach;?>

        <?php endif;?>

        <?php if(isset($message)):?>
            <h3> <?=$message?> </h3>
        <?php endif;?>
        <div class="row" style="justify-content: center;">
            <?php App\Core\FormBuilder::render($form)?>    
        </div>
    </div>
</main>    
    
<script>
    var form            = document.getElementById("booking_form");
    var bookPplNumber   = form.elements['number'];
    var bookDate        = form.elements['date'];
    var bookTime        = form.elements['time'];
    var bookSubmit      = form.elements[form.elements.length - 1]
    step1();

    function step1(){
        bookDate.setAttribute('type', 'hidden');
        bookTime.setAttribute('type', 'hidden');
        bookTime.remove();
        bookSubmit.setAttribute('type', 'hidden');

        let nextBtn = document.createElement('input');
        nextBtn.setAttribute('id', 'step1');
        nextBtn.setAttribute('type', 'button');
        nextBtn.setAttribute('onclick', 'step2(' + bookPplNumber.value + ')');
        nextBtn.value = 'Next';
        form.append(nextBtn);
    }

    async function step2(nb){
        let res = await fetchNumber(nb);
        if(!res)
        {
            return;
        }
        let nextBtn = document.getElementById('step1');
        if(nextBtn) nextBtn.remove();
        bookPplNumber.setAttribute('type', 'hidden');
        bookDate.setAttribute('type', 'date');

        nextBtn = document.createElement('input');
        nextBtn.setAttribute('id', 'step2');
        nextBtn.setAttribute('type', 'button');
        nextBtn.setAttribute('onclick', 'step3()');
        nextBtn.value = 'Next 2';
        form.append(nextBtn);
    }

    async function step3(){
        let chosenDate  = bookDate.value;
        let number      = bookPplNumber.value;
        let res = await fetchHours(chosenDate, number);
        if(!res)
        {
            return;
        }
        let hoursDiv = document.createElement('div');
        console.log(res.times)
        if(res.times?.length > 0){
            res.times.forEach( time => hoursDiv.append(createTimeInput(time)));
            form.append(hoursDiv);
        }else{
            return;
        }
        let nextBtn = document.getElementById('step2');
        if(nextBtn) nextBtn.remove();
        bookSubmit.setAttribute('type', 'submit');

    }

    function createTimeInput(item){
        let radio = document.createElement('input');
        radio.setAttribute('type', 'radio');
        radio.setAttribute('name', 'time');
        radio.setAttribute('value', item );

        let label = document.createElement('label');
        label.innerHTML = item;

        let radioDiv = document.createElement('div');
        radioDiv.append(radio);
        radioDiv.append(label);
        return radioDiv;
    }

    async function fetchNumber(value){
        try{
            let res = await fetch('<?=DOMAIN?>/site/<?=$this->site->getSubDomain()?>/ent/api/booking/number?number=' + value, 
            {
                method: 'GET',
                headers:{
                    'Content-type': 'application/x-www-form-urlencoded'
                },
            }).then( (res) => { if(res?.status === 200 && res?.redirected != true){ return res.json();}});

            if(res?.code === 200){
                return true;
            }else{
                throw new Error('Wrong response code');
            }
        }catch(e){
            console.error(e);
            return false;
        }
    }


    async function fetchHours(date, number){
        console.log('date= ' + date);
        console.log('time= ' + number);
        try{
            let res = await fetch('<?=DOMAIN?>/site/<?=$this->site->getSubDomain()?>/ent/api/booking/time?date=' + date +'&number=' + number, 
            {
                method: 'GET',
                headers:{
                    'Content-type': 'application/x-www-form-urlencoded'
                },
            }).then( (res) => { if(res?.status === 200 && res?.redirected != true){ return res.json();}})

            if(res?.code === 200){
                return res;
            }else{
                throw new Error('Wrong response code');
            }
        }catch(e){
            console.error(e);
            return false;
        }
    }

</script>