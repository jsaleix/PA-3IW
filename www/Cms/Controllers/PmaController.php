<?php

namespace CMS\Controller;

use CMS\Models\Post_Medium_Association as PMAssoc;

use CMS\Core\CMSView as View;

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

    public function deleteAssocFromPostAction($site){
        if(empty($_GET['id']))
			\App\Core\Helpers::customRedirect('/admin/medium', $site);
        $PMAObj = new PMAssoc($site['prefix']);
        $PMAObj->setId($_GET['id']??0);
        $pma = $PMAObj->findOne();
        if(!$pma)
            \App\Core\Helpers::customRedirect('/admin/medium', $site);
        $link = "/admin/article/edit?id=".$pma["post"];
        $PMAObj->delete();
        \App\Core\Helpers::customRedirect($link, $site);
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