<?php
class ControllerAppApi extends RestController {
    
    protected $allowMethod    = array('get','post','put'); // REST允许的请求类型列表
    protected $allowType      = array('html','xml','json'); // REST允许请求的资源类型列表
    protected $version        = 'v1';
    protected $document_url   = '';

    public function index(){
        $this->version='v1';
        $this->namespace = 'api';
        $this->patterns = array(
            '/cats' => 'categories/index@get',
            '/cats/:id/products' => 'categories/products@get',
            '/cats/:id/products/:id' => 'products/detail@get',
            '/users/:id' => 'users/index@post',
            '/login' => 'login/index@post',
        );
        //var_dump($this->request->server);
        $result = $this->createRoute();
        //$code = isset($result['code']) ? $result['code'] : 200;
        //$msg = isset($result['message']) ? $result['message'] : '';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($result));
        //$this->result($result,'json',$code);
    }

}