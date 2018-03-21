<?php
class ControllerAppTest extends Controller {
    public function index(){
        $json = array();

        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}