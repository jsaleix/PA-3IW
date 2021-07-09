<?php

namespace CMS\Controller;

use CMS\Core\CMSView as View;
use App\Models\Site as Site;

class ContactsController{

    public function defaultAction($site){
        $siteObj = new Site();
        $siteObj->setId($site['id']);

        $view = new View("contacts", "back", $site);
        
        if(!empty($_POST)){
            ["action" => $action] = $_POST;

            if($action === "contact"){
                ["phoneNumber" => $phoneNumber, "emailPro" => $emailPro, "address" => $address] = $_POST;
                
                if(!empty($phoneNumber) && !is_null($phoneNumber)){
                    $siteObj->setPhoneNumber($phoneNumber);
                }else{
                    $siteObj->setPhoneNumber("IS NULL");
                }

                if(!empty($emailPro) && !is_null($emailPro)){
                    $siteObj->setEmailPro($emailPro);
                }else{
                    $siteObj->setEmailPro("IS NULL");
                }

                if(!empty($address) && !is_null($address)){
                    $siteObj->setAddress($address);
                }else{
                    $siteObj->setAddress("IS NULL");
                }

                $siteObj->save();

            }
            if($action === "socials"){
                ["instagram" => $instagram, "twitter" => $twitter, "facebook" => $facebook] = $_POST;

                if(!empty($instagram) && !is_null($instagram)){
                    $siteObj->setInstagram($instagram);
                }else{
                    $siteObj->setInstagram("IS NULL");
                }

                if(!empty($twitter) && !is_null($twitter)){
                    $siteObj->setTwitter($twitter);
                }else{
                    $siteObj->setTwitter("IS NULL");
                }

                if(!empty($facebook) && !is_null($facebook)) { 
                    $siteObj->setFacebook($facebook);
                }else{
                    $siteObj->setFacebook("IS NULL");
                }

                $siteObj->save();
            }

            $siteObj = new Site();
            $siteObj->setId($site['id']);
            $site = $siteObj->findOne();

        }

        $contactForm = $siteObj->formContactEdit($site);
        $socialForm = $siteObj->formSocialEdit($site);

        $view->assign("contactForm", $contactForm);
        $view->assign("socialForm", $socialForm);
        $view->assign("site", $site);
    }

}

?>