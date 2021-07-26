<h1><?= $title ?></h1>
<hr/>

<div class="row">
    <div class ="col-6 col-md-12 col-sm-12" style="padding: 1em; padding-top:0;">
        <h2>Current theme inuse: <span style="color:#2DC091"><?= $site->getTheme(); ?></span></h2>

        <h3>Change theme to: </h3>
        <?php App\Core\FormBuilder::render($form)?>
        <h3>Change styles: </h3>
        
        <?php App\Core\FormBuilder::render($formStyles)?>
        <div id="stylesDiv">

        </div>
    </div>
    <div class="col-6 col-md-12 col-sm-12" style="padding: 1em; padding-top:0;">
        <h3>Preview:</h3>
        <div class="thumbnail-slider">
            <div class="arrow-container">
                <div onclick="changeSlide(slideIndex-1)" class="arrow">
                    <svg width="26" height="30" viewBox="0 0 26 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.9824 14.0039L20.8753 6.03516C21.4208 5.48438 22.303 5.48438 22.8427 6.03516L24.1543 7.35938C24.6999 7.91016 24.6999 8.80078 24.1543 9.3457L18.5597 14.9941L24.1543 20.6426C24.6999 21.1934 24.6999 22.084 24.1543 22.6289L22.8485 23.9648C22.303 24.5156 21.4208 24.5156 20.8811 23.9648L12.9883 15.9961C12.4369 15.4453 12.4369 14.5547 12.9824 14.0039ZM1.83959 15.9961L9.73245 23.9648C10.278 24.5156 11.1601 24.5156 11.6999 23.9648L13.0115 22.6406C13.557 22.0898 13.557 21.1992 13.0115 20.6543L7.41682 15.0059L13.0115 9.35742C13.557 8.80664 13.557 7.91602 13.0115 7.37109L11.6999 6.04688C11.1543 5.4961 10.2722 5.4961 9.73245 6.04688L1.83959 14.0156C1.29406 14.5547 1.29406 15.4453 1.83959 15.9961Z" fill="#9E2DC0"/>
                    </svg>
                </div>
                <div onclick="changeSlide(slideIndex+1)" class="arrow">
                    <svg width="26" height="30" viewBox="0 0 26 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.0176 15.9961L5.12469 23.9648C4.57916 24.5156 3.69701 24.5156 3.15728 23.9648L1.84568 22.6406C1.30014 22.0898 1.30014 21.1992 1.84568 20.6543L7.44032 15.0059L1.84568 9.35742C1.30014 8.80664 1.30014 7.91602 1.84568 7.37109L3.15148 6.03516C3.69701 5.48438 4.57916 5.48438 5.11889 6.03516L13.0117 14.0039C13.5631 14.5547 13.5631 15.4453 13.0176 15.9961ZM24.1604 14.0039L16.2676 6.03516C15.722 5.48438 14.8399 5.48438 14.3001 6.03516L12.9885 7.35937C12.443 7.91016 12.443 8.80078 12.9885 9.3457L18.5832 14.9941L12.9885 20.6426C12.443 21.1934 12.443 22.084 12.9885 22.6289L14.3001 23.9531C14.8457 24.5039 15.7278 24.5039 16.2676 23.9531L24.1604 15.9844C24.7059 15.4453 24.7059 14.5547 24.1604 14.0039Z" fill="#9E2DC0"/>
                    </svg>
                </div>
            </div>
            <?php if(count($thumbnails) != 0): ?>
                <?php foreach($thumbnails as $thumbnail): ?>
                    <img class="slide" src="<?= DOMAIN."/".$thumbnail ?>" />
                <?php endforeach;?>
            <?php endif; ?>
            <?php if(count($thumbnails) == 0): ?>
                <center>
                <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5869 3.59031C14.2724 3.51775 13.9586 3.71344 13.8858 4.02793C13.813 4.34241 14.0092 4.65644 14.3237 4.72922C15.7775 5.06522 17.1064 5.80336 18.1664 6.86332C21.2826 9.97954 21.2826 15.0504 18.1664 18.1666C15.0501 21.2831 9.97926 21.2831 6.86303 18.1666C3.74658 15.0504 3.74658 9.97954 6.86303 6.86332C7.75773 5.96862 8.81654 5.31608 10.0102 4.92331C10.3166 4.82261 10.4835 4.49233 10.3828 4.18563C10.2818 3.87893 9.95134 3.71207 9.64486 3.81301C8.27569 4.26322 7.0617 5.01143 6.03654 6.03682C2.46438 9.60898 2.46438 15.4212 6.03654 18.9934C7.82251 20.7793 10.1685 21.6724 12.5148 21.6724C14.8609 21.6722 17.2069 20.7793 18.9931 18.9934C22.565 15.4212 22.565 9.60898 18.9931 6.03682C17.778 4.82192 16.2545 3.97574 14.5869 3.59031V3.59031Z" fill="#9E2DC0"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M29.2039 25.3539L23.0791 19.229C26.2198 14.304 25.532 7.83348 21.3643 3.66554C19.0006 1.30188 15.8578 0 12.515 0C9.17198 0 6.0292 1.30188 3.66554 3.66554C1.30165 6.02921 0 9.17198 0 12.515C0 15.8578 1.30165 19.0006 3.66554 21.3643C6.0292 23.728 9.17175 25.0296 12.5146 25.0296C14.9137 25.0296 17.2247 24.3567 19.229 23.0791L25.3539 29.2039C25.8664 29.7166 26.5501 29.9989 27.2791 29.9989C28.0078 29.9989 28.6915 29.7166 29.2042 29.2039C30.2653 28.1424 30.2653 26.4155 29.2039 25.3539V25.3539ZM28.3775 28.3775C28.0856 28.6691 27.6956 28.8297 27.2791 28.8297C26.8623 28.8297 26.4722 28.6691 26.1804 28.3775L19.7173 21.9143C19.6042 21.801 19.4545 21.7431 19.3039 21.7431C19.1881 21.7431 19.0718 21.7772 18.9709 21.8472C17.0718 23.1644 14.8391 23.8607 12.5146 23.8607C9.48395 23.8607 6.63483 22.6806 4.49203 20.5378C0.0682068 16.114 0.0682068 8.91586 4.49203 4.49203C6.63506 2.34901 9.48418 1.1689 12.515 1.1689C15.5457 1.1689 18.3948 2.34901 20.5378 4.49203C24.4215 8.37593 24.9724 14.4651 21.8472 18.9709C21.6861 19.2032 21.7142 19.5172 21.9143 19.7173L28.3775 26.1804C28.9831 26.786 28.9831 27.7716 28.3775 28.3775V28.3775Z" fill="#9E2DC0"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.0432 8.98658C15.815 8.75838 15.4452 8.75838 15.2167 8.98658L12.515 11.6883L9.81307 8.98658C9.58487 8.75838 9.21477 8.75838 8.98658 8.98658C8.75838 9.21477 8.75838 9.58487 8.98658 9.81307L11.6883 12.515L8.98658 15.2167C8.75838 15.4449 8.75838 15.815 8.98658 16.0432C9.10056 16.1574 9.25025 16.2144 9.39971 16.2144C9.5494 16.2144 9.69886 16.1574 9.81307 16.0432L12.5148 13.3415L15.2165 16.0432C15.3307 16.1574 15.4804 16.2144 15.6299 16.2144C15.7796 16.2144 15.929 16.1574 16.0432 16.0432C16.2714 15.815 16.2714 15.4449 16.0432 15.2167L13.3413 12.515L16.0432 9.81307C16.2714 9.58487 16.2714 9.21477 16.0432 8.98658V8.98658Z" fill="#9E2DC0"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12.1275 4.53072C12.4493 4.53072 12.7119 4.26819 12.7119 3.94615C12.7119 3.62434 12.4493 3.36182 12.1275 3.36182C11.8055 3.36182 11.543 3.62434 11.543 3.94615C11.543 4.26819 11.8055 4.53072 12.1275 4.53072Z" fill="#9E2DC0"/>
                </svg>
                </center>
            <?php endif; ?>
        </div> 
    </div>
</div>

<script>
    var slideIndex = 1;
    
    function changeSlide(n){
        slideIndex = n;
        showSlide();
    }

    function showSlide(){
        var slides = $(".slide");

        for(let i = 0; i<slides.length; i++){
            slides[i].style = "display:none;";
        }

        if(slideIndex > slides.length){
            slideIndex = 1;
        }
        
        if(slideIndex < 1){
            slideIndex = slides.length;
        }
            
        slides[slideIndex-1].style = "display:block;";
    }
    
    showSlide(slideIndex);
</script>

<script>
    async function getStyleConf(){
        var div = document.getElementById('stylesDiv');
        div.innerHTML = "";

        var element = document.getElementById('stylesConf').value;

        let form    = document.createElement("form");
        form.method = "POST";
        form.action = "";
        
        div.append(form);

        let res = await fetch('<?=DOMAIN?>/site/<?=$site->getSubDomain();?>/admin/api/getstyle?element=' + element, 
            {
                method: 'GET',
                headers:{
                    'Content-type': 'application/json'
                },
            })
            .then((res)=>res.json());
            
            if(res.code === 200){
                var i = 0;
                Object.entries(res.style).forEach(([firstKey, firstValue]) => {

                    let input   = document.createElement("input");
                    let label = document.createElement("label");

                    input.placeholder = firstKey;

                    input.setAttribute("class","input input-100");
                    input.id = i;

                    label.setAttribute("for",i);
                    label.innerHTML = firstKey + ": ";
                    form.setAttribute("class","col-10");

                    Object.entries(firstValue).forEach(([key, value]) => {
                        // input.setAttribute("type","text");

                        if(key == "type"){
                            input.setAttribute(key,value);
                        }
                        if((key == "display") && (value == true)){
                            form.append(label);
                            form.append(input);
                        }
                    });
                    i++;
                });

                let submit = document.createElement("input");
                submit.setAttribute("class","btn btn-100");
                submit.type = "submit";
                submit.value = "UPDATE STYLES";

                form.append(submit);
            }
        
    }
    getStyleConf();
</script>