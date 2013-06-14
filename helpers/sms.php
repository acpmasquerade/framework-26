<?php
	
class Helper_SMS extends Helper {

	public static function push($to, $message, $log_data = array()){
		$client_id = Config::get("sparrowsms_client_id");
		$username = Config::get("sparrowsms_username");
		$password = Config::get("sparrowsms_password");

		$from = Config::get("sparrowsms_from");

		// build the query arguments
		$query_parameters = http_build_query(array(
			"client_id" => $client_id,
			"username" => $username,
			"password" => $password,
			"from" => $from,
			"to" => $to,
			"text" => $message
		));
		 
		// STEP 2
		// build the url
		$api_url = "http://api.sparrowsms.com/call_in.php?{$query_parameters}";

		// create a new cURL resource
		$ch = curl_init();

		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $api_url);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);

		// grab URL and pass it to the browser
		$response = curl_exec($ch);

		list($response_headers, $response_content) = explode("\r\n\r\n", $response, 2);

		// close cURL resource, and free up system resources
		curl_close($ch);

		$response_headers_array = explode("\r\n", $response_headers, 2);
		$http_response_code = explode(" ", $response_headers_array[0]);

		$return = array(
			"version"=>$http_response_code[0],
			"code"=>$http_response_code[1],
			"status"=>$http_response_code[2],
			"headers"=>$response_headers_array[1], 
			"content"=>$response_content, 
			);

		// Add the outgoing log to server
		// on behalf of username
		$db_log_data = array();
		$db_log_data["message"] = $message;
		$db_log_data["created"] =  date("Y-m-d H:i:s", time());
		$db_log_data["credits"] = ceil(strlen($message)/160);

		if(isset($log_data["total_unread"])){
			$db_log_data["total_unread"] = $log_data['total_unread'];
		}else{
			$db_log_data["total_unread"] = 0;
		}

		if(isset($log_data["total_mofald"])){
			$db_log_data["total_mofald"] = $log_data['total_mofald'];
		}else{
			$db_log_data["total_mofald"] = 0;
		}

		if(isset($log_data["username"])){
			$db_log_data["username"] = $log_data['username'];
		}else{
			$db_log_data["username"] = 0;
		}

		$db_log_data['response'] = $response_content;
		$db_log_data["response_code"] = $return["code"];
		$db_log_data["response_status"] = $return["status"];

		$db_log_data["phone"] = $to;

		self::db()->insert("outgoing_logs", $db_log_data);

		return $return;
		 
		// STEP 2
		// put the request to server
		// $response = file_get_contents($api_url);
		// // check the response and verify

		// $return =  array("headers"=>$http_response_header, "response"=>$response);
		// mdie($return);
	}
}