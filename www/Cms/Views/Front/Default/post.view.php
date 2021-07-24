<main class="main-container">
       <div class="col-10 article-container">
           <?php if(!isset($notFound)): ?>

                <h1><?=$post['title']?></h1>
                <h2>By <span><?=$post['author']?></span> the <span><?= (new DateTime($post['publicationDate']))->format("d/m/y")?></span> at <span><?= (new DateTime($post['publicationDate']))->format("H:i")?></span></h2>
                <hr/>
                <p>
                    <?=$post['content']?>
                </p>
                <?php if(isset($medias) && !empty($medias)):?>
                    <section id="medias_section">
                        <?php foreach ($medias as $media):?>
                            <img src="<?= DOMAIN . $media['image']?>" alt="<?= $media['name'] ?>">
                        <?php endforeach;?>
                    </section>
                <?php endif ?>

                <?php if($post['allowComment']):?>
                    <hr/>
                    <?php if($canPostComment): ?>
                        <?php App\Core\FormBuilder::render($commentForm)?>    
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
                        <div class="comment col-6 col-md-10 col-sm-12" id="<?= $comment['id']?>">
                            <h2><span><?=$comment['author']?></span> the <b><?= $comment['date'] ?> </b></h2>
                            <p><?=$comment['message']?></p>
                        </div>
                    <?php endforeach;?>
                <?php endif ?>
            <?php else: ?>
                <h1>Not Found :/</h1>
                <hr/>
                <p>Sorry, we're unable to find the article you're looking for.</p>
            <?php endif; ?>



       </div>
</main>

<style>
    #medias_section{
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    #medias_section img{
        margin-right: 10px;
        margin-bottom: 10px;
        width: 25%;
        object-fit: cover;
    }
</style>