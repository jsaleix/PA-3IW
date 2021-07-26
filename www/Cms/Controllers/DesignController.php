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
        
        $thumbnails = $this::getThumbnails($siteObj);

        // Styles
        $config = $this::getStylesConfiguration($siteObj);
        $elements = $this::getElements($config);

        $formStyles = $siteObj->formStylesEdit($elements);
        
        $view->assign("site", $siteObj);
        $view->assign("form", $form);
        $view->assign("formStyles", $formStyles);
        $view->assign("thumbnails", $thumbnails);
    }

    public function getStylesAction($site){
        http_response_code(200);
        header('Content-Type: application/json');

        try{
            if(!isset($_GET['element'])){
                echo "Element not set";
                http_response_code(400);
                throw new \Exception('Element not set');
            }

            $configJson = $this::getStylesConfiguration($site);
            $config = json_decode($configJson, true);
            $styles = [];

            foreach($config['elements'] as $elementName => $element){
                if($elementName == $_GET['element']){
                    $styles = $element;
                }
            }

            $styles["code"] = 200;
            
            if(count($styles) == 0){
                http_response_code(404);
                $styles['code'] = 404;
            }
            
            $styles = json_encode($styles);
            echo $styles;

        }catch(\Exception $e){
            $code = 200;  
        }
    }

    private function getElements($config){
        $configArray = json_decode($config, true);
        $elements = [];

        foreach(array_keys($configArray['elements']) as $element){
            $elements[$element]=$element;
        }

        return $elements;
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