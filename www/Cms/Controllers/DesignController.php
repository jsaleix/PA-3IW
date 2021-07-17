<?php

namespace CMS\Controller;

use CMS\Core\CMSView as View;

use App\Models\Site as Site;

class DesignController{

    public function defaultAction($site){
        $siteObj = $site;

        $themes = [];
        $thumbnails = [];

        $view = new View("design", "back", $site);
        $view->assign("title","Design | Themes | Styles");

        foreach (glob("Cms/Views/Front/*") as $theme){
            $tmpTheme = explode("/", $theme)[3];
            $themes[$tmpTheme] = $tmpTheme;
        } 

        $form = $siteObj->formThemeEdit($themes);

        if(!empty($_POST)){
            if(($_POST['theme'] !== $site->getTheme()) && array_search($_POST['theme'], $themes)){
                $siteObj->setTheme($_POST['theme']);
                $siteObj->save();
                $site = $siteObj->findOne();
            }
            
        }

        foreach (glob("Cms/Views/Front/".$siteObj->getTheme()."/Thumbnails/*") as $thumbnail){
            $imageFileType = strtolower(pathinfo($thumbnail,PATHINFO_EXTENSION));
            if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif")
                array_push($thumbnails, $thumbnail);
        } 

        $view->assign("site", $siteObj);
        $view->assign("form", $form);
        $view->assign("thumbnails", $thumbnails);
    }


}   


?>