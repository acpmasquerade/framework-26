<?php
	
/**
 * A Bonus Helper for SMS communication via Sparrow SMS. (api.sparrowsms.com)
 * Get the access credentials from the provider
 */
class Helper_SparrowSMS extends Helper {

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

		// You may store the outgoing records somewhere, if you require.
		
		return $return;		
	}
}