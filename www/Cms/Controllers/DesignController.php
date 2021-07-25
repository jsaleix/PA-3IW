<?php

namespace CMS\Controller;

use CMS\Core\CMSView as View;

use App\Models\Site as Site;
use App\Core\FormValidator;


class DesignController{

    public function defaultAction($site){
        $siteObj = $site;

        $view = new View("design", "back", $site);
        $view->assign("title","Design | Themes | Styles");

        $themes = $this::getThemes();

        $form = $siteObj->formThemeEdit($themes);
        $formStyles = $siteObj->formStylesEdit("");

        if(!empty($_POST)){
            if($_POST['type'] && $_POST['type'] === "themes"){
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
            if($_POST['type'] && $_POST['type'] === "styles"){

            }
        }

        $this::getStylesConfiguration($siteObj);

        $thumbnails = $this::getThumbnails($siteObj);
        
        $view->assign("site", $siteObj);
        $view->assign("form", $form);
        $view->assign("formStyles", $formStyles);
        $view->assign("thumbnails", $thumbnails);
    }


    private function getStylesConfiguration($siteObj){
        $configFile = $_SERVER['DOCUMENT_ROOT'].'/public/Assets/cms/Front/Default/Styles/config.json';

        if(file_exists($_SERVER['DOCUMENT_ROOT'].'/public/Assets/cms/Front/'.$siteObj->getTheme()."/Styles/config.json")){
            $configFile = $_SERVER['DOCUMENT_ROOT'].'/public/Assets/cms/Front/'.$siteObj->getTheme()."/Styles/config.json";
        }

        return file_get_contents($configFile);
    }

    private function getThemes(){
        $themes = [];
        $path = $_SERVER['DOCUMENT_ROOT'] ."/Cms/Views/Front";
        foreach (glob($path . '/*') as $theme){
            $tmpTheme = explode("/", $theme);
            $tmpTheme = $tmpTheme[count($tmpTheme)-1];
            $themes[$tmpTheme] = $tmpTheme;
        }
        return $themes;
    }
    private function getThumbnails($siteObj){
        $thumbnails = [];

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
        return $thumbnails;
    }

    // public function styleModifierAction($site){

    // }


}   


?>