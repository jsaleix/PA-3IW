<h1>Contacts</h1>
<hr/>
<div class="row">
    <div class="col-6 col-md-12 col-sm-12" style="padding-right: 1em;">
        <h3>Contact Informations</h3>
        <hr/>
        <?php $contact = App\Core\FormBuilder::render($contactForm); ?>
    </div>
    <div class="col-6 col-md-12 col-sm-12" style="padding-right: 1em;">
        <h3>Social Informations</h3>
        <hr/>
        <?php $social = App\Core\FormBuilder::render($socialForm); ?>
    </div>
</div>

<script>
    changeCsrf();

    function changeCsrf(){
        let socialCSRF = document.forms.socials.CSRF;
        let contactCSRF = document.forms.contact.CSRF;
        contactCSRF.value = socialCSRF.value;
    }
</script>