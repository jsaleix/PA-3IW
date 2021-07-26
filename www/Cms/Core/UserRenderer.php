<?php

namespace CMS\Core;

use App\Core\Helpers;
use App\Core\Security;
use App\Models\User;

class UserRenderer
{

	public function render($classname = null): void
	{
		if(!Security::isConnected()) 
            return;
        $user = Security::getUser();
        $userObj = new User();
        $userObj->setId($user);
        $userObj->findOne(TRUE);
        $avatar = $userObj->getAvatar() ? DOMAIN . "/". $userObj->getAvatar() : "https://png.pngtree.com/png-vector/20190710/ourlarge/pngtree-user-vector-avatar-png-image_1541962.jpg";

        $profileLink = DOMAIN .  '/profile?id=' . $userObj->getId() ;
        $html = '<a class="'. $classname .'" href="' . $profileLink . '">';
        $html .= "<p>" . $userObj->getFirstname() ." " . $userObj->getLastname() . "</p>
            <img class=\"avatar\" src=" . $avatar . ">";
        $html .= '</a>';
        echo $html;

    }

}






