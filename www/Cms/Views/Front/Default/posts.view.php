<main class="main-container">
    <div class="col-10 col-md-11 col-sm-11 posts-container">
        <h1>Articles</h1>
        <hr/>
        <?php foreach ($posts as $post): ?>
            
            <div class="article-display col-11 col-md-11 col-sm-12">
                <h2><a href="ent/post?id=<?= $post['post']['id'] ?>"><?= $post['post']['title'] ?></a></h2>
                <p><?=substr($post['post']['content'], 0, 250)?></p>
                <br/>
                <p>By <b><?= $post['publisher']['firstname']." ".$post['publisher']['lastname'] ?></b> the <span><?= (new DateTime($post['post']['publicationDate']))->format('d/m/y') ?></span> at <span><?= (new DateTime($post['post']['publicationDate']))->format('H:i') ?></span></p>
                <hr/>
            </div>
            
        <?php endforeach; ?>
    </div>
</main>