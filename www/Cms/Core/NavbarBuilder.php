<?php

namespace CMS\Core;
use CMS\Models\Page;

class NavbarBuilder
{

	public function __construct(){
	}

	/* Backoffice navBar */
	/*
	* $site array
	* $type String
	*/
    public static function renderNavbar($site, $type = 'front'){
		if($type === 'front'){
			return self::renderFrontNavbar($site);
		}else{
			return self::renderBackNavbar($site);
		}
	}

	public static function renderBackNavbar($site){
		$url = $site['subDomain'];
		$html = '<nav><ul>';
		$html .= "<li><a href='/site/${url}/admin/'>Dashboard</a></li>";

		$html .= "<li>Pages</li>";
		$html .= "<ul><li><a href='managepages'>Manage</a></li></ul>";
		$html .= "<ul><li><a href='createpage'>Create</a></li></ul>";

		$html .= "<li>Articles</li>";
		$html .= "<ul><li><a href='managearticles'>Manage</a></li></ul>";
		$html .= "<ul><li><a href='createarticle'>Create</a></li></ul>";

		$html .= "<li><a href='/site/${url}/admin/'>Events</a></li>";
		$html .= "<ul><li><a href='/site/${url}/admin/'>Add</a></li></ul>";
		
		$html .= "<li><a href='/site/${url}/admin/'>Users</a></li>";
		$html .= "<li><a href='/site/${url}/admin/'>Media library</a></li>";
		$html .= "<li><a href='/site/${url}/admin/'>Roles</a></li>";
		$html .= "<li><a href='/site/${url}/admin/'>Mailing</a></li>";
		$html .= "<li><a href='/site/${url}/admin/settings'>Settings</a></li>";
		$html .= "</ul></nav>";
		return $html;
	}

	public function renderFrontNavbar($site){
        $pageObj = new Page(null, $site['prefix']);
        $pageObj->setCategory('IS NULL');
        $pagesToShow = $pageObj->findAll();
        $html = "<h1>" . $site['name'] . "'s restaurant</h1>";
        $html .= '<nav><ul>';
        foreach($pagesToShow as $tab){
            $html .= '<li><a href="/site/' . $site['subDomain'] . '/' . $tab['name'] . '"/>' . $tab['name'] . '</a></li>';
        }
        $html .= '</ul></nav>';

        return $html;
    }

}






