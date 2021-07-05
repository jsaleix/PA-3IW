<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;

use CMS\Models\Page;
use CMS\Models\Comment;
use CMS\Models\Post;

use CMS\Core\CMSView as View;
use CMS\Core\NavbarBuilder;

class EventController{

	public function manageEventsAction($site){
		$view = new View('back/createEvent', 'back', $site);
		/*$view->assign("fields", $fields);
		$view->assign("datas", $datas);*/
		$view->assign('pageTitle', "Manage the events");
	}

}