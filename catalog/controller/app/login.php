<?php
class ControllerAppLogin extends Controller {
    private $error = array();
    public function index($args) {
        $this->load->model('account/customer');
        $this->load->language('account/login');

        if(isset($args['email'])){
            $email = $args['email'];
        }else{
            $email = '';
        }
        if(isset($args['password'])){
            $password = $args['password'];
        }else{
            $password = '';
        }
        if($this->validate($email,$password)){
            $token = array(
                "iss" => $this->ssl ? HTTPS_SERVER : HTTP_SERVER,
                "aud" => $this->ssl ? HTTPS_SERVER : HTTP_SERVER,
                "iat" => strtotime("now"),
                "nbf" => strtotime("now"),
                'jti'=> sha1($this->customer->getId().$this->customer->getEmail().$this->customer->getTelephone()),
                'exp'=> strtotime("+3 day"),
                'sub'=>"mailto:".$this->config->get('config_email'),
                'data' => array(
                    'username'=> $email,
                    'password'=> $password
                )
            );
            $json = array(
                'access_token'  => $this->jwt->encode($token, JWT_SECRET, 'HS256'),
                'expires_in'    => strtotime("+3 day"),
                //'refresh_token' => 
            );
                  
        }
        if($this->error){
            return $this->error;
        }
        return $json;
    }

    protected function validate($email,$password) {
        // Check how many login attempts have been made.
        $login_info = $this->model_account_customer->getLoginAttempts($email);

        if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
            $this->error['warning'] = $this->language->get('error_attempts');
        }

        // Check if customer has been approved.
        $customer_info = $this->model_account_customer->getCustomerByEmail($email);

        if ($customer_info && !$customer_info['status']) {
            $this->error['warning'] = $this->language->get('error_approved');
        }

        if (!$this->error) {
            if (!$this->customer->login($email, $password)) {
                $this->error['warning'] = $this->language->get('error_login');

                $this->model_account_customer->addLoginAttempt($email);
            } else {
                $this->model_account_customer->deleteLoginAttempts($email);
            }
        }

        return !$this->error;
    }
}
