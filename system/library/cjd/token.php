<?php
namespace Cjd;
require_once(DIR_SYSTEM . 'library/cjd/jwt/src/SignatureInvalidException.php');
require_once(DIR_SYSTEM . 'library/cjd/jwt/src/JWT.php');
use \Firebase\JWT\JWT;

class Token {
	public function __construct($registry) {
		$this->leeway = JWT::$leeway;
	}
	public function encode($token,$key,$type='HS256'){
		return JWT::encode($token, $key,$type);
	}
	public function decode($token,$key,$type=array('HS256')){
		return JWT::decode($token, $key, $type);
	}
}
