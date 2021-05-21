<?php
namespace CMS\Controller;
use App\Core\Database as db;
use App\Models\User;
use App\Models\Site;

use CMS\Models\Page;
use CMS\Models\Content;

class PageRenderer 
{
    private $site;
    private $page;
    private $category = null;
    private $path;
    private $error = null;

	public function __construct($url){
        $this->path     = $url;
        $this->domain   = $url[0];
        if(empty($url[1])){ $url[1] = 'home'; }
        $this->setParams($url);
	}

    private function setParams($url){
        $siteData = new Site();
        $siteData->setSubDomain($this->domain);
        $site = $siteData->findOne();
        if(empty($site['id'])){
            $this->error = 'This website does not exist :/';
            return;
        }

        $siteData->setId($site['id']);
        $siteData->setName($site['name']);
        $siteData->setDescription($site['description']);
        $siteData->setImage($site['image']);
        $siteData->setCreator($site['creator']);
        $siteData->setSubDomain($site['subDomain']);
        $siteData->setPrefix($site['prefix']);
        $siteData->setType($site['type']);
        $this->site = $siteData;

        $pageName = $url[1];
        /*$category = [];
        while( count($url) > 1 ){
            $removed = array_shift($url);
            if($removed){
                $category[] = $removed;
            }
        }
        $this->category = $category;*/
        $page = new Page($pageName, $this->site->getPrefix());
        $pageData = $page->findOne();
        if(empty($pageData['id'])){
            $this->error = 'The requested page does not exist :/';
            return;
        }
        $page->setId($pageData['id']);
        $this->page = $page;
    }

    public function renderContent($content){
        $publisherData = new User();
        extract($content);
        $publisherData->setId($publisher);
        $publisher = $publisherData->findOne();
        
		switch($type){
			case 'article':
				$html = '<h2>' . $title . '</h2>';
				$html .= '<p id='. $publisher['id'] .' >By ' . $publisher['firstname'] . ' ' .  $publisher['lastname'] . '</p>';
				$html .= '<p>' . $content . '</p>';
				$html .= '<hr>';
				break;

			default: 
			    return;
		}
        echo $html;
	}

    public function renderPage(){
        $this->renderNavigation();

        if($this->error){
            echo $this->error;
            return;
        }

        $contentObj = new Content(null, null, null, null);
        $contentObj->setTableName($this->site->getPrefix());
        $contentObj->setPage($this->page->getId());
        $contents = $contentObj->findAll();

        
        if(count($contents) === 0){
            echo 'No content found :/';
            return;
        }

        foreach($contents as $content){
            $contentObj = new Content($content['title'], $content['content'], $content['page'], $content['publisher']);
            $this->renderContent($contentObj->returnData());
        }
        
    }
    
    public function renderNavigation(){
        $pageObj = new Page(null, $this->site->getPrefix());
        $pageObj->setCategory('IS NULL');
        $pagesToShow = $pageObj->findAll();
        $html = "<header>";
        $html .= "<h1>" . $this->site->getName() . "'s restaurant</h1>";
        $html .= '<ul>';
        foreach($pagesToShow as $tab){
            $html .= '<li><a href="/site/' . $this->site->getSubDomain() . '/' . $tab['name'] . '"/>' . $tab['name'] . '</a></li>';
        }
        $html .= '</ul>';
        $html .= "</header>";

        echo $html;
    }


}
