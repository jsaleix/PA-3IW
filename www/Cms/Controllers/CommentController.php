<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;

use CMS\Models\Page;
use CMS\Models\Comment;
use CMS\Models\Post;

use CMS\Core\View;
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
		$fields = [ 'Id', 'Message', 'Post', 'Author', 'Date' ];
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

				$formalized = "\"" . $item['id'] . "\",\"" . $item['message'] . "\",\"" . $item['idPost'] .  "\",\"" . $item['idUser'] . "\",\"" . $item['date'] . "\"";
				$datas[] = $formalized;
			}
		}
		
		$view = new View('back/list', 'back');
		$view->assign("navbar", navbarBuilder::renderNavBar($site, 'back'));
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the comments");
	}

}