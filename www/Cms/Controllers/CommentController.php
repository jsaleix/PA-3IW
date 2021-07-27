<?php

namespace CMS\Controller;
use App\Models\User;
use App\Core\Security;

use CMS\Models\Comment;
use CMS\Models\Post;

use CMS\Core\CMSView as View;

class CommentController{

	public function manageCommentsAction($site){//List the comments and btn to manage them
		$commentObj = new Comment($site->getPrefix());
		$comments = $commentObj->findAll();

        $userObj = new User();
        $postObj = new Post($site->getPrefix());

        $content = "";//Prepare data for the render in front
		$fields = [ 'Id', 'Message', 'Post', 'Author', 'Date', 'Delete' ];
		$datas = [];

		if($comments){//If there is comments, formalize to render them
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
                    if($username) {
                        $item['user'] = $username['firstname'] . ' ' . $username['lastname'];
                        $item['user'] = "<a href='" . DOMAIN . '/profile?id='. $item['idUser'] ."'>".$item['user']."</a>";
                    }
                }else{
                    $item['user'] = 'Unknown';
                }
                $buttonDelete = "<a href='comment/delete?id=" .$item['id']."'>Go</a>";

				$formalized = "\"" . $item['id'] . "\",\"" . htmlspecialchars($item['message']) . "\",\"" . $item['idPost'] .  "\",\"" . $item['user'] . "\",\"" . $item['date']. "\",\"" . $buttonDelete . "\"";
				$datas[] = $formalized;
			}
		}
		
		$view = new View('list', 'back', $site);
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the comments");
	}

    public function deleteCommentAction($site){//Delete comment from id in admin
        try{
            if(!isset($_GET['id']) || empty($_GET['id']) ){ throw new \Exception('comment not set'); }
            $commentObj = new Comment($site->getPrefix());
            $commentObj->setId($_GET['id']??0);
            $comment = $commentObj->findOne();

            if(!$comment){ throw new \Exception('Cannot delete this comment'); }//Check if the comment exists
            $check = $commentObj->delete();

            if(!$check){ throw new \Exception('Cannot delete this comment');}//Check the deletion
			\App\Core\Helpers::customRedirect('/admin/comments?success', $site);
        }catch(\Exception $e){
			echo $e->getMessage();
			\App\Core\Helpers::customRedirect('/admin/comments?error', $site);
		}
        
    }

    public function deleteMyCommentAction($site){//Delete comment from id in front, can be used only by the user that created it
        try{
            if(!isset($_GET['id']) || empty($_GET['id'])){ throw new \Exception('no comment specified'); }

            $commentObj = new Comment($site->getPrefix());//Try to find the comment
            $commentObj->setId($_GET['id']??0);
            $comment = $commentObj->findOne();
            if(!$comment || $comment['idUser'] != Security::getUser()){ throw new \Exception('Cannot delete this comment'); }//Check that it's the creator that tries to delete it

            $check = $commentObj->delete();
            if(!$check){ throw new \Exception('Cannot delete this comment');}//Check the deletion
            \App\Core\Helpers::customRedirect('/ent/post?id='.$comment['idPost'], $site);
            
        }catch(\Exception $e){
            echo $e->getMessage();
            \App\Core\Helpers::customRedirect('/ent/post?error', $site);
        }
    }

}