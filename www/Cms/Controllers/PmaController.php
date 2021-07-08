<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;
use App\Models\Action;
use App\Core\FileUploader;
use App\Core\FormValidator;
use App\Core\Security;

use CMS\Models\Content;
use CMS\Models\Page;
use CMS\Models\Post;
use CMS\Models\Post_Medium_Association as PMAssoc;
use CMS\Models\Category;
use CMS\Models\Dish;
use CMS\Models\DishCategory;
use CMS\Models\Medium;

use CMS\Core\CMSView as View;
use CMS\Core\NavbarBuilder;

class PmaController{

    public function defaultAction($site){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('admin', 'back', $site);
		$view->assign('pageTitle', "Dashboard");
		$view->assign('content', $html);
	}

    public function createAssocAction($site){
        if(empty($_GET['post']) && empty($_GET['medium']))
            \App\Core\Helpers::customRedirect('/admin/medium', $site);
        $PMAObj = new PMAssoc($site['prefix']);
        $PMAObj->setMedium(htmlspecialchars($_GET['medium']));
        $PMAObj->setPost(htmlspecialchars($_GET['post']));
        $pma = $PMAObj->save();
        if( $pma )
            \App\Core\Helpers::customRedirect('/admin/article/edit?id='.$PMAObj->getPost(), $site);
    }

    public function deleteAssocAction($site){
        if(empty($_GET['id']))
			\App\Core\Helpers::customRedirect('/admin/medium', $site);
        $PMAObj = new PMAssoc($site['prefix']);
        $PMAObj->setId($_GET['id']??0);
        $pma = $PMAObj->findOne();
        if(!$pma)
            \App\Core\Helpers::customRedirect('/admin/medium', $site);
        $link = "/admin/medium/edit?id=".$pma["medium"];
        $PMAObj->delete();
        \App\Core\Helpers::customRedirect($link, $site);
    }
}