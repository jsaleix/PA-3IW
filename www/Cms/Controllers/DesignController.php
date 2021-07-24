<?php

namespace CMS\Controller;

use CMS\Core\CMSView as View;

use App\Models\Site as Site;
use App\Core\FormValidator;


class DesignController{

    public function defaultAction($site){
        $siteObj = $site;

        $themes = [];
        $thumbnails = [];

        $view = new View("design", "back", $site);
        $view->assign("title","Design | Themes | Styles");

        $path = $_SERVER['DOCUMENT_ROOT'] ."/Cms/Views/Front";
        foreach (glob($path . '/*') as $theme){
            $tmpTheme = explode("/", $theme);
            $tmpTheme = $tmpTheme[count($tmpTheme)-1];
            $themes[$tmpTheme] = $tmpTheme;
        }

        $form = $siteObj->formThemeEdit($themes);

        if(!empty($_POST)){
            if(($_POST['theme'] !== $site->getTheme()) && array_search($_POST['theme'], $themes)){
                $errors = FormValidator::check($form, $_POST);
                if( count($errors) > 0){
                    $view->assign("errors", $errors);
                    return;
                }
                $siteObj->setTheme($_POST['theme']);
                $siteObj->save();
                $site = $siteObj->findOne();
            }
            
        }

        foreach (glob( $_SERVER['DOCUMENT_ROOT'] . '/public/Assets/cms/Front/'.$siteObj->getTheme()."/Thumbnails/*") as $thumbnail){
            $imageFileType = strtolower(pathinfo($thumbnail,PATHINFO_EXTENSION));
            if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif")
            {
                #Removing /var/www/html/ from the path
                $thumbnail = explode('/', $thumbnail);
                $thumbnail = array_slice($thumbnail, 4);
                $thumbnail = implode('/', $thumbnail);
                array_push($thumbnails, $thumbnail);
            }
        } 

        $view->assign("site", $siteObj);
        $view->assign("form", $form);
        $view->assign("thumbnails", $thumbnails);
    }


}   


?>