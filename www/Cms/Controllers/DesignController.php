<?php

namespace CMS\Controller;

use CMS\Core\CMSView as View;

use App\Models\Site as Site;

class DesignController{

    public function defaultAction($site){
        $siteObj = new Site();
        $siteObj->setId($site['id']);

        $themes = [];

        $view = new View("design", "back", $site);
        $view->assign("title","Design | Themes | Styles");

        foreach (glob("Cms/Views/Front/*") as $theme){
            $tmpTheme = explode("/", $theme)[3];
            $themes[$tmpTheme] = $tmpTheme;
        } 

        $form = $siteObj->formThemeEdit($themes);

        if(!empty($_POST)){
            if(($_POST['theme'] !== $site['theme']) && array_search($_POST['theme'], $themes)){
                $siteObj->setTheme($_POST['theme']);
                $siteObj->save();
                $site = $siteObj->findOne();
            }
            
        }

        $view->assign("site", $site);
        $view->assign("form", $form);
        
    }


}   


?>