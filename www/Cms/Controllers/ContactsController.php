<?php

namespace CMS\Controller;

use CMS\Core\CMSView as View;
use App\Models\Site as Site;
use App\Core\FormValidator;
use App\Core\Helpers as Helpers;

class ContactsController{

    public function defaultAction($site){
        $siteObj = $site;

        $view = new View("contacts", "back", $site);
        $contactForm = $siteObj->formContactEdit();
        $socialForm = $siteObj->formSocialEdit();

        
        if(!empty($_POST)){
            ["action" => $action] = $_POST;
            if($action === "contact"){
                $errors = FormValidator::check($contactForm, $_POST);
                if( count($errors) > 0){
                    $view->assign("errors", $errors);
                    return;
                }
                $adding = $siteObj->edit($_POST);
                if($adding){
                    $message ='Contact succesfully modified!';
                    $view->assign("alert", Helpers::displayAlert("success",$message,3500));
                    $contactForm = $siteObj->formContactEdit();
                }else{
                    $view->assign("errors", "An error from our servers occured, try again later");
                }
            }
            if($action === "socials"){
                $errors = FormValidator::check($socialForm, $_POST);
                if( count($errors) > 0){
                    $view->assign("errors", $errors);
                    return;
                }
                $adding = $siteObj->edit($_POST);
                if($adding){
                    $message ='Socials succesfully modified!';
                    $view->assign("alert", Helpers::displayAlert("success",$message,3500));
                    $socialForm = $siteObj->formSocialEdit();
                }else{
                    $view->assign("errors", "An error from our servers occured, try again later");
                }
            }

        }

        
        $view->assign("contactForm", $contactForm);
        $view->assign("socialForm", $socialForm);
        $view->assign("site", $site);
    }

}

?>