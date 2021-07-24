<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;

use App\Core\Security;
use App\Core\ErrorReporter;
use App\Core\Helpers;

use CMS\Models\Page;
use CMS\Models\Post;
use CMS\Models\Dish;

class SitemapController{


	public function renderSiteMapAction($site){
        $pageObj = new Page($site->getPrefix());
        $pages = $pageObj->findAll();
        $dishObj = new Dish($site->getPrefix());
        $dishes = $dishObj->findAll();
        $postObj = new Post($site->getPrefix());
        $posts = $postObj->findAll();
        
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

        if($dishes && count($dishes) != 0){
                foreach($dishes as $dish){
                        echo '<url>' . PHP_EOL;
                        echo '<loc>'. Helpers::renderCMSLink('ent/dish?id='.$dish['id'], $site) .'/</loc>' . PHP_EOL;
                        echo '<changefreq>daily</changefreq>' . PHP_EOL;
                        echo '</url>' . PHP_EOL;
                }
        }

        if($posts && count($posts) != 0){
                foreach($posts as $post){
                        echo '<url>' . PHP_EOL;
                        echo '<loc>'. Helpers::renderCMSLink('ent/post?id='.$post['id'], $site) .'/</loc>' . PHP_EOL;
                        echo '<changefreq>daily</changefreq>' . PHP_EOL;
                        echo '</url>' . PHP_EOL;
                }
        }
        
        echo '</urlset>' . PHP_EOL;
	}

}