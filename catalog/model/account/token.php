<?php
class ModelAccountToken extends Model {
	public function addToken($data) {
		if(isset($data)){
			$this->db->query('INSERT INTO '. DB_PREFIX .'jwt_token SET customer_id="'. $data['customer_id'] .'", token="'. $data['token'] .'", expired=FROM_UNIXTIME("'. $data['expired'] .'")');
		}
	}
	public function getToken($token) {
		$query = $this->db->query('SELECT * FROM '. DB_PREFIX .'jwt_token WHERE token="' . $token . '"');
		if($query->num_rows){
			return $query->row;
		}
		return false;
	}
	public function refreshToken() {

	}
}