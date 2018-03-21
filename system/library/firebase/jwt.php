<?php
namespace Jwt;
class Jwt {
	public function __construct($registry) {
		require_once(DIR_SYSTEM . 'library/firebase/jwt/src/JWT.php');
	}
	public function encode($token,$key){
		return JWT::encode($token, $key);
	}
	public function decode($token){
		return JWT::decode($token, $this->key, array('HS256'));
	}
}