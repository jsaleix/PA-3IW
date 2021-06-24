<h2><?=$post['title']?></h2>
<p id='<?=$publisher['id']?>'>By <?=$post['author']?></p>
<p><?=$post['content']?></p>
<hr>
<?php if(isset($errors) && !empty($errors)):?>
    <?php foreach ($errors as $error):?>
        <li style="color:red"><?=$error;?></li>
    <?php endforeach;?>
<?php endif ?>

<?php if($post['allowComment']):?>
    <?php if($canPostComment): ?>
        <button onClick="toggleBox()">Publish a comment</button>
        <form id="commentBox" action="" method="POST">
            <input name="message"/>
            <input type="submit" value="Publish"/>
        </form>
    <?php else: ?>
        <button>You must be logged in to post a comment</button>
    <?php endif;?>
<?php endif;?>

<?php if(isset($comments) && !empty($comments)):?>
    <?php foreach ($comments as $comment):?>
        <p><?=$comment['message']?></p>
        <i>Published by <?= $comment['author'] ?> - <?=$comment['date']?></i>
        <p>###############</p>
    <?php endforeach;?>
<?php endif ?>

<script>
    function toggleBox(){
        let box = document.getElementById('commentBox');
        let style = getComputedStyle(box);
        if(style.display === 'none'){
            box.style.display = 'flex';
        }else{
            box.style.display = 'none';
        }
    }
</script>

<style>
    #commentBox{
        display: none;
        flex-direction: row;
        justify-items: flex-start;
        align-items: flex-end;
    }

    #commentBox:nth-child(1){
        padding-bottom: 2em;
    }
</style>
