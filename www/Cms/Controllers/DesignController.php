<?php

namespace CMS\Controller;

use CMS\Core\CMSView as View;

class DesignController{

    public function defaultAction($site){

        $view = new View("design", "back", $site);

        $view->assign("title","Design | Themes | Styles");
    }
}   


?>