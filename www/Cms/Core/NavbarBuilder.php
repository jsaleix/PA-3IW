<?php

namespace CMS\Core;

class NavbarBuilder
{

	public function __construct(){
	}

    public static function renderNavbar($site){
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
		$html .= "<li><a href='/site/${url}/admin/'>Advanced</a></li>";
		$html .= "</ul></nav>";
		return $html;
	}

}






