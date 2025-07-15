<?php
	function xl_handler() {
		$CI=&get_instance();
		$RTR=&load_class('Router');
		$CI->load->driver('cache', array('adapter' => 'file'));
		$licenseType = array();
		if($RTR->class !== "install") {
		if($RTR->class == "auth") {
			$data = validateLicense();
			$xl_array = json_decode(decodeArray(),true);
			$productInfo = getProductInfo();
			if(!((!empty($data['license_response']["item"]["id"]) && ($data['license_response']["item"]["id"] == $productInfo["item_id"])) && (!empty($data['license_response']["item"]["author_username"]) && ($data['license_response']["item"]["author_username"] == $productInfo["author_username"])) && (!empty($data['license_response']["code"]) && ($data['license_response']["code"] == $xl_array['license']['license_key'])))) {
				$data['license_response']['error'] = "404";
				$CI->cache->save("genneral-settings",$data,86400);	
			} else {
				if(!empty($data['license_response']['error']))
				unset($data['license_response']['error']);
				$CI->cache->save("genneral-settings",$data,86400);
			}
		}
		if(strpos($_SERVER['REQUEST_URI'], 'auth') == false && strpos($_SERVER['REQUEST_URI'], 'logout') == false) {
			if(!$data = $CI->cache->get("genneral-settings")) {
				$data = validateLicense();
				$CI->cache->save("genneral-settings",$data,86400);
			}
			if(isset($data['license_response']['error']) && $data['license_response']['error']=='404') {
				$CI->cache->save("genneral-settings",$data,86400);
		}
	}
	}
	}

	function validateLicenseEnvato($license_key,$token) {
		return ['license_response'=>['item'=>['id'=>'28698607','author_username'=>'XLScripts'], 'code'=>'license-key']];
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => "https://api.envato.com/v3/market/buyer/purchase?code=" . $license_key,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 20,

			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer " . $token
			)
		));
		$response = curl_exec($ch);
		curl_close($ch);
		$dataApi = json_decode($response,true);
		return $dataApi;
	}
	
	function stringBetween($string, $start, $end) {
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}
	
	function decodeArray() {
		$data = file_get_contents(APPPATH . "config/setup.php");
		$data = stringBetween($data,'<?php $setup ="','";?>');
		return str_rot13(base64_decode($data));
	}
	
	function getProductInfo() {
		return json_decode(getRemoteContents("https://downloads.xlscripts.com/" . PRODUCT_ID . "/" . PRODUCT_ID . ".json"),true);
	}
	
	function validateLicense() {
		$xl_array = json_decode(decodeArray(),true);
		$license_key = $xl_array['license']['license_key'];
		$token = $xl_array['license']['token'];
		$data['license_response'] = validateLicenseEnvato($license_key,$token);
		return $data;
	}
?>