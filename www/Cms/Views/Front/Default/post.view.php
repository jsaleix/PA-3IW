<main class="main-container">
       <div class="col-10 article-container">
            <h1><?=$post['title']?></h1>
            <h2>Par <span><?=$post['author']?></span> le <span>18/04/2021</span> à <span>18h09</span></h2>
            <hr/>
            <p>
                <?=$post['content']?>
            </p>


            <?php if($post['allowComment']):?>
                <hr/>
                <?php if($canPostComment): ?>
                    <form action="" method="POST">
                        <input name="message" class="input input-100 comment-input" placeholder="Écrire un commentaire"/>
                        <button type="submit" class="btn comment-btn">Commenter</button>
                    </form>
                <?php endif;?>
            <?php endif;?>

            <?php if(isset($errors) && !empty($errors)):?>
                <?php foreach ($errors as $error):?>
                    <li style="color:red"><?=$error;?></li>
                <?php endforeach;?>
            <?php endif ?>

            <?php if(isset($comments) && !empty($comments)):?>
                <h3>Commentaires</h3>
                <?php foreach ($comments as $comment):?>
                    <div class="comment col-6 col-md-10 col-sm-12">
                        <h2><span><?=$comment['author']?></span> le <b><?=  (new DateTime($comment['date']))->format("d/m/y à h:i") ?> </b></h2>
                        <p><?=$comment['message']?></p>
                    </div>
                <?php endforeach;?>
            <?php endif ?>


       </div>
</main>