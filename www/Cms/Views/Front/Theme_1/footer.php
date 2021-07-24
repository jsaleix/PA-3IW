<footer>
        <div class="col-4">
            <h2><?= $site->getName(); ?></h2>
            <ul>
                <li><a href="#">Links</a></li>
                <li><a href="#">Links</a></li>
                <li><a href="#">Links</a></li>
                <li><a href="#">Links</a></li>
                
            </ul>
    
            <br/>
            <p><?= $site->getName(); ?> Copyright © 2021-2022</p>
        </div>
        <div class="row">
                <div class="row" style="height: 100%;">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
                <div class="row social-container">
                    <?php if($site->getFacebook() != NULL): ?>
                        <a href="<?= $site->getFacebook() ?>" target="_blank" class="social-btn"><img src=<?= DOMAIN."/Assets/images/icons/facebook.png" ?> alt="Facebook" /></a>
                    <?php endif; ?>
                    <?php if($site->getInstagram() != NULL): ?>
                        <a href="<?= $site->getInstagram() ?>" target="_blank" class="social-btn"><img src=<?= DOMAIN."/Assets/images/icons/instagram.png"?> alt="Instagram" /></a>
                    <?php endif; ?>
                    <?php if($site->getTwitter() != NULL): ?>
                        <a href="<?= $site->getTwitter() ?>" target="_blank" class="social-btn"><img src=<?= DOMAIN."/Assets/images/icons/twitter.png" ?> alt="Twitter" /></a>
                    <?php endif; ?>
                </div>
        </div>
    </footer>