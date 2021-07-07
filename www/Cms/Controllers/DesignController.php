<?php

namespace CMS\Controller;

use CMS\Core\CMSView as View;

use App\Models\Site as Site;

class DesignController{

    public function defaultAction($site){
        $siteObj = new Site();
        $siteObj->setId($site['id']);


        $themes = [];

        foreach (glob("Cms/Views/Front/*") as $theme){
            $tmpTheme = explode("/", $theme)[3];
            $themes[$tmpTheme] = $tmpTheme;
        } 

        $form = $siteObj->formThemeEdit($themes);

        $view = new View("design", "back", $site);

        $view->assign("title","Design | Themes | Styles");
        $view->assign("site", $site);
        $view->assign("form", $form);

        if(!empty($_POST)){
            if(($_POST['theme'] !== $site['theme']) && array_search($_POST['theme'], $themes)){
                $siteObj->setTheme($_POST['theme']);
                $siteObj->save();
                
                header("Refresh:0");
            }
            
        }

        
    }


}   


?>