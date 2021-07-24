<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;
use App\Core\FileUploader;
use App\Core\Security;
use App\Core\FormValidator;
use App\Core\ErrorReporter;
use App\Core\Helpers;

use CMS\Core\CMSView as View;
use CMS\Core\StyleBuilder;
use CMS\Models\Page;

class SitemapController{


	public function renderSiteMapAction($site){
        $pageObj = new Page($site->getPrefix());
        $pages = $pageObj->findAll();

        header("Content-Type: application/xml; charset=utf-8");
        echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL; 
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL;
        foreach($pages as $page)
        {
         echo '<url>' . PHP_EOL;
         echo '<loc>'. Helpers::renderCMSLink($page['name'], $site) .'/</loc>' . PHP_EOL;
         echo '<changefreq>daily</changefreq>' . PHP_EOL;
         echo '</url>' . PHP_EOL;
        }
        
        echo '</urlset>' . PHP_EOL;
	}

}