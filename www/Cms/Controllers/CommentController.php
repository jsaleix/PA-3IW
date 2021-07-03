<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;

use CMS\Models\Page;
use CMS\Models\Comment;
use CMS\Models\Post;

use CMS\Core\CMSView as View;
use CMS\Core\NavbarBuilder;

class CommentController{

	public function manageCommentsAction($site){
		$commentObj = new Comment();
		$commentObj->setPrefix($site['prefix']);
		$comments = $commentObj->findAll();

        $userObj = new User();
        $postObj = new Post();
        $postObj->setPrefix($site['prefix']);

        $content = "";
		$fields = [ 'Id', 'Message', 'Post', 'Author', 'Date', 'Delete' ];
		$datas = [];

		if($comments){
			foreach($comments as $item){
                if($item['idPost'] !== NULL){
                    $postObj->setId($item['idPost']);
                    $postName = $postObj->findOne();
                    $item['idPost'] = $postName['title'];
                }else{
                    $item['idPost'] = 'Unknown';
                }

                if($item['idUser'] !== NULL){
                    $userObj->setId($item['idUser']);
                    $username = $userObj->findOne();
                    if($username) $item['idUser'] = $username['firstname'] . ' ' . $username['lastname'];
                }else{
                    $item['idUser'] = 'Unknown';
                }
                $buttonDelete = "<a href='deletecomment?id=" .$item['id']."'>Go</a>";

				$formalized = "\"" . $item['id'] . "\",\"" . htmlspecialchars($item['message']) . "\",\"" . $item['idPost'] .  "\",\"" . $item['idUser'] . "\",\"" . $item['date']. "\",\"" . $buttonDelete . "\"";
				$datas[] = $formalized;
			}
		}
		
		$view = new View('back/list', 'back', $site);
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the comments");
	}

    public function deleteCommentAction($site){
        if(!isset($_GET['id']) || empty($_GET['id']) ){
			echo 'comment not found ';
			header("Location: managecomments");
			exit();
		}

        $commentObj = new Comment();
        $commentObj->setPrefix($site['prefix']);
        $commentObj->setId($_GET['id']??0);
        $comment = $commentObj->findOne();
        if(!$comment){
			header("Location: managecomments");
			exit();
        }
        $commentObj->delete();
        header("Location: managecomments");
		exit();
    }

}