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
            '/search/:id' => 'search/index@get',
            '/users/:id' => 'users/index@post',
            '/login' => 'login/index@get',
            '/token'    => 'token/index@post',
            '/user/:id'    => 'users/index@post'
        );
        $result = $this->createRoute();
        //var_dump($this->request->get['route']);
        if(isset($this->request->server['HTTP_AUTHORIZATION'])){
            $token = explode(' ', $this->request->server['HTTP_AUTHORIZATION']);
            $isVaildate = $this->validate_token($token[1]);
            if(!$isVaildate){
                header('HTTP/1.1 401 Unauthorized'); 
                header('status: 401 Unauthorized'); 
                $result = array(
                    'msg'   =>  'unauthorized',
                    'code'  =>  401
                );
                $this->response->setOutput(json_encode($result));
            }else{
                $expired = $isVaildate['expired'];
                var_dump($expired);
                echo date('y-m-d h:i:s');
                echo date($expired);
                var_dump(strtotime(date($expired)) - strtotime(date('y-m-d h:i:s')));
                if(strtotime(date($expired)) - strtotime(date('y-m-d h:i:s')) < 0){
                    $result = array(
                        'msg'   =>  '登陆已过期，请重新登陆',
                        'code'  =>  10200
                    );
                }
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($result));
            }
        }else{
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($result));
        }
    }
    private function validate_token($token) {
        $this->load->model('account/token');
        try{
            $key = JWT_SECRET;
            $decoded = $this->model_account_token->getToken($token);
            if(!$decoded){
                return false;
            }
            return $decoded;
        }
        catch(Exception $e)
        {
            return false;
        }
    }

}