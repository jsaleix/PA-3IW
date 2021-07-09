<?php

namespace CMS\Controller;

use CMS\Core\CMSView as View;

class EventController{

	public function manageEventsAction($site){
		$view = new View('createEvent', 'back', $site);
		/*$view->assign("fields", $fields);
		$view->assign("datas", $datas);*/
		$view->assign('pageTitle', "Manage the events");
	}

}