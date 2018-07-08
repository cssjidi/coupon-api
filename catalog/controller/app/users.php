<?php
class ControllerAppUsers extends RestController {
    public function index($args) {
    	$data = array();
    	$uid = $args['first_id'];
    	if($uid != $this->customer->getId()){
			$data = array(
	            'msg'           => '无权限访问该页',
	            'code'          => 10100
	        );
    	}else{
    		$data = array(
	    		'email' => $this->customer->getEmail(),
	    		'phone' => $this->customer->getTelephone(),
	    		'name'  => $this->customer->getFirstName()
	    	);
    	}
    	return $data;
    }
}
