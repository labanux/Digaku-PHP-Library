<?php
/**
 * Library PHP untuk API Digaku
 * Dokumentasi terkait : http://developer.digaku.com
 * Author : Okto Silaban (@labanux)
 * Author URL : http://okto.silaban.net
 * Version : 1.0
 * Last changed : Dec 15th, 2010
 * 
 **/
class Digaku {
	
	// Sesuaikan dengan api_key anda
	var $api_key = 'ed46136370057d6f99db08150c3edef2b0dc185b';
	
	// Sesuaikan dengan client_id / app_id anda
	var $client_id = '4d07146afccf226557000000';
	
	// Sesuaikan dengan client_secret anda
	var $client_secret = 'dfda2e3ff7293b499fa09f8aa86b49f48334e60c';
	
	var $api = 'http://api.digaku.com/';
	var $auth = 'http://auth.digaku.com/';
	
	// Sesuaikan dengan URL untuk callback untuk mendapatkan 'code'
	var $redirect_uri = 'http://localhost/digaku/callback-code.php';
	
	var $access_token = null;
	var $code = null;

	function __construct($access_token = null) {
		$this->access_token = $access_token;
	}
	
	/**
	 * Mengambil informasi user
	 *
	 **/
	function my_info() {
		return $this->getApi('my/info', array('access_token' => $this->access_token));
	}

	/**
	 * Fetch URL
	 *
	 * Parameter :
	 *  <string> $url Base URL, atau Base URL + GET parameter
	 *  <string> $method Metode 'post' atau 'get'
	 *  <string> $post_fields Parameter POST / GET
	 *  <int> $post_fields_count Banyaknya field
	 *
	 *  Return :
	 *  Jika error : false
	 *  Jika sukses : <string> JSON
	 **/
	private function _fetch($url, $method = 'get', $post_fields = null, $post_fields_count = 0) {
		$ch = curl_init();
		$timeout = 5;
		
		curl_setopt($ch, CURLOPT_URL, $url);
				
		if($method == 'post') {
			curl_setopt($ch,CURLOPT_POST, $post_fields_count);
			curl_setopt($ch,CURLOPT_POSTFIELDS, $post_fields);
		}
		
		// Jika request anda ingin dikenali sebagai user agent tertentu, hilangkan comment baris dibawah, sesuaikan
		//curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9');
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		
		// Mengambil content-type di header
		$content_type = explode(';', curl_getinfo($ch, CURLINFO_CONTENT_TYPE));
		
		curl_close($ch);
		
		if($content_type[0] == 'text/xml') {
			$error_msg = simplexml_load_string($data);
			echo $error_msg->code.' '.$error_msg->desc; // Kalau tidak ingin langsung ditampilkan, comment baris ini
			return false;			
		} else {
			return $data;
		}
		
	}
	
	/**
	 * Memanggil API ke Digaku
	 *
	 * Parameter :
	 * <string> $api_method Metode API yang dipanggil, misal : my/info, user/stream
	 * <array> $params Parameter yang dibutuhkan ketika merequest API
	 * <string> $method Request 'get' atau 'post'
	 *
	 * Jika error : false
	 * Jika sukses : <string> JSON
	 **/
	public function getApi($api_method, $params = array(), $method = 'get') {
		$base_url = $this->api.$api_method.'?';
		$param_count = count($params);

		$field_params = null;

		foreach($params as $key => $val){
			$field_params = $field_params.$key.'='.$val.'&';			
		}

		$field_params = rtrim($field_params,'&');

		if($method == 'get') {
			$base_url = $base_url.$field_params;
		}

		return $this->_fetch($base_url, $method, $field_params, $param_count);
	}
	
	/**
	 * Request Token
	 **/
	public function authorizeToken($code) {
		// This is will redirect you to Digaku login page
		header('Location: '.$this->auth.'access_token?code='.$code.'&client_secret='.$this->client_secret);
	}
	
	/**
	 * Set nilai Token
	 **/
	function setToken($token) {
		$this->access_token = $token;
	}
	
	/**
	 * Request Code
	 **/
	public function authorizeCode() {
		header('Location: '.$this->auth.'authorize?client_id='.$this->client_id.'&redirect_uri='.$this->redirect_uri);
	}
	
	/**
	 * Set nilai Code
	 **/
	private function setCode($code) {				
		$this->code = $code;
	}
	
	
}
?>
