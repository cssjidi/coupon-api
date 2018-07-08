<?php
class ControllerProductTaobao extends Controller {
	public function index() {
		$key = '232323asdf23';
		$token = array(
		    "iss" => "http://example.org",
		    "aud" => "http://example.com",
		    "iat" => 1356999524,
		    "nbf" => 1357000000
		);
		print_r($this->jwt->encode($token,$key));
	}
}
