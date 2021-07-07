<main class="main-container">
       <div class="col-10 article-container">
            <h1><?=$post['title']?></h1>
            <h2>By <span><?=$post['author']?></span> the <span><?= (new DateTime($post['publicationDate']))->format("d/m/y")?></span> at <span><?= (new DateTime($post['publicationDate']))->format("H:i")?></span></h2>
            <hr/>
            <p>
                <?=$post['content']?>
            </p>


            <?php if($post['allowComment']):?>
                <hr/>
                <?php if($canPostComment): ?>
                    <form action="" method="POST">
                        <input name="message" class="input input-100 comment-input" placeholder="Write a comment"/>
                        <button type="submit" class="btn comment-btn">Publish</button>
                    </form>
                <?php endif;?>
            <?php endif;?>

            <?php if(isset($errors) && !empty($errors)):?>
                <?php foreach ($errors as $error):?>
                    <li style="color:red"><?=$error;?></li>
                <?php endforeach;?>
            <?php endif ?>

            <?php if(isset($comments) && !empty($comments)):?>
                <h3>Comments</h3>
                <?php foreach ($comments as $comment):?>
                    <div class="comment col-6 col-md-10 col-sm-12">
                        <h2><span><?=$comment['author']?></span> the <b><?= $comment['date'] ?> </b></h2>
                        <p><?=$comment['message']?></p>
                    </div>
                <?php endforeach;?>
            <?php endif ?>


       </div>
</main>