
<div class="row" >
<div class="col-12 col-sm-12 col-md-12 col-xl-12">
        <div class="col-inner">
            <div class="pageTitle">
                <h2 style="font-weight: lighter;"><?=$pageTitle?></h2>
                <?php if(isset($createButton)): ?>
                    <a href="<?= $createButton['link']?>"><button class="cta-green"><?=$createButton['label']?></button></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="row" style="justify-content: center;">
    <?php if( !empty( $settings ) && $settings == true ):?>
        <?php App\Core\FormBuilder::render($form, TRUE)?>  
    <?php endif;?>
    <?php if( !empty( $planning ) && $planning == true ):?>
        <div style="display: flex; flex-direction: column;">
            <p>Planning</p><br>
            <div>
                <?php //if( !empty($forms) ):?>
                    <?php //foreach($forms as $f):?>
                        <?php App\Core\FormBuilder::render($f, TRUE);?>  
                    <?php //endforeach;?>
                <?php// endif;?>
            </div>
        </div>
    <?php endif;?>
</div>


<style>
    *{
        -webkit-appearance: auto;
        scrollbar-width: auto;
    }

    #form_content div{
        display: flex;
        flex-direction: column;
    }

    #form_content div div{
        display: flex;
        flex-direction: row;
    }

    form input{
        margin-bottom: 10px;
        background-color: transparent;
        border: 1px solid #2DC091;
        color: black;
        padding: 0.8em;
        padding-left: 1em;
        padding-right: 1em;
        font-weight: normal;
        outline: none;
        font-size: 16px;
    }

    #planning_form {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
    }

    #planning_form div:not(.radio, .radio-option){
        flex-basis: calc(100% / 5);
        border-top: 1px solid gray;
        padding-top: 10px;
    }
    
</style>

<script>
    var planningForm = document.getElementById('planning_form');
    if(planningForm){
        setTimeout(()=>{
            shapeForm(planningForm);
        }, 3000)
    }

    function shapeForm(rawForm){
        let idx=0;
        let tmpChilds = [];

    }
</script>