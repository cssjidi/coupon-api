<?php
class ControllerAppSearch extends RestController {
	public function index($arg){
		$keyword = urldecode($arg['first_id']);
		$json = array();
        $json = $this->taobao->getTaobaoSearch($keyword);
        return $json;
	}
}