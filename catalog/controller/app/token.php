<?php
class ControllerAppToken extends Controller {
	public function index()
	{
		$json = array();
		if(!isset($this->request->post['email']) || !isset($this->request->post['password'])) {
			$json['msg'] = '密码或用户名不能为空';
		}elseif(isset($this->request->post['email']) && $this->request->post['password']) {
			$this->load->model('account/token');
			$customer_info = $this->customer->login($this->request->post['email'], $this->request->post['password']);
			if($customer_info){
				$key = JWT_SECRET;
				$host = 'http://' . $this->request->server['HTTP_HOST'];
				if(isset($this->request->server['HTTPS'])){
					$host = 'https://' . $this->request->server['HTTP_HOST'];
				};
				//var_dump($this->request->server['HTTP_HOST']);
				$token = array(
				    "iss" => $host,
				    "aud" => $host,
				    "iat" => $this->request->server['REQUEST_TIME'],
				    "exp" => $this->request->server['REQUEST_TIME'] + 86400 * 3,
				    "nbf" => $this->request->server['REQUEST_TIME'],
				    'email' => $this->customer->getEmail(),
				    'telephone' => $this->customer->getTelephone(),
				    'password' => $this->request->post['password'],
				);
				$jwt['token'] = $this->jwt->encode($token, $key,'HS256');
				$data = array(
					'token'			=>	$this->jwt->encode($token, $key),
					'expired'		=>	$this->request->server['REQUEST_TIME'] + 86400 * 3,
					'customer_id' 	=> $this->customer->getId()
				);
				$this->model_account_token->addToken($data);
				$jwt['uid'] = $this->customer->getId();
				$jwt['expired'] = $this->request->server['REQUEST_TIME'] + 86400 * 3;
				//$decoded = $this->jwt->decode($jwt, $key, array('HS256'));
				return $jwt;
			}
			$json['msg'] = '密码或用户名错误';
			return $json;
		}
	}
}