<?php

namespace CMS\Controller;

use App\Models\Action;

class ActionController{


	public function getActionFiltersAction($site){
        $code = 200;
        $itemsArr = [];

        try{
            if(!isset($_GET['id'])){
                throw new \Exception('Action not set');
            }
            $actionId = $_GET['id'];
            $actionObj = new Action();
            $actionObj->setId($actionId);
            $action = $actionObj->findOne();

            if(!$action){ 
                throw new \Exception('Action not set');
            }

            if(empty($action['filters']) || strlen($action['filters']) < 1){
                throw new \Exception('No filter for this action');
            }
            $actionObj = '\\CMS\Models\\'.ucfirst($action['filters']);
            $obj = new $actionObj();
            $obj->setPrefix($site['prefix']);
            $items = $obj->findAll();

            $code = 200;

            foreach($items as $item){
				$itemsArr[] = array(
					'id' => $item['id'],
					'name' => (isset($item['name']) ? $item['name'] : $item['title'])
				);
			}

        }catch(\Exception $e){
            $code = 200;  
        }
		http_response_code($code);
        echo json_encode(array('code' => $code, 'results' => ($itemsArr)));
	}


}