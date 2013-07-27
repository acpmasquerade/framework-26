<?php

	if(!function_exists("redirect")){
		/**
		 * Redirect to a specific location. Retains the notifications if any.
		 * @param $url = URL to be redirected to
		 * @param $http_code = HTTP Status Code to be forced, ONLY (201, and 3XX are supported). Refer: http://php.net/manual/en/function.header.php
		 * @param $headers = extra headers array if required
		 */
		function redirect($url, $http_code = NULL, $headers = array()){
			// check notifications
			if(Template::has_notifications()){
				Template::persist_notifications();
			}

			if(isset($http_code)){
				header("HTTP/1.1 {$http_code}");
			}

			if($headers AND is_array($headers)){
				foreach($headers as $h){
					header("{$h}");
				}
			}

			header("Location: {$url}");
			
			exit;
		}
	}


	if(!function_exists("mdie")){
		function mdie($var){
			echo "<pre>";
			print_r($var);
			echo "</pre>";
			die();
		}
	}

	if(!function_exists("ndie")){
		function ndie($var){
			echo "<pre>";
			print_r($var);
			echo "</pre>";
		}
	}

	if(!function_exists("to_array")){
		function to_array($object){
			if(is_object($object)){
				$array = array();
				foreach($object as $key=>$val){
					$array["{$key}"] = $val;
				}

				return $array;
			}

			if(is_array($object)){
				return $object;
			}

			return array($object);
		}
	}


	/** Is a valid phone number or not **/
	function is_valid_phone_number($phone_number){
	    $number_pattern = "/^(977)?9[6-8][0-9]{8}$/";
	    return(preg_match($number_pattern, $phone_number));
	}


	if(!function_exists("ajax_die")){
		function ajax_die($var = array()){
			die(json_encode($var));
		}
	}

	function random_password() {
	    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
	    $pass = array(); //remember to declare $pass as an array
	    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
	    return implode($pass); //turn the array into a string
	}

	if(!function_exists("form_post_data")){
		function form_post_data($fields_array = array()){
			$return_data = array();

		    if (!is_array($_POST)) {
		        return null;
		    }

		    if (is_array($fields_array)) {
		        foreach ($fields_array as $key) {
		        	if(isset($_POST["$key"])){
		            	$return_data[$key] = $_POST["{$key}"];
		        	} else {
		        		$return_data[$key] = "";
		        	}
		        }
		    } else {
		        foreach ($_POST as $key => $val) {
		            $return_data[$key] = $_POST["{$key}"];
		        }
		    }
			return $return_data;
		}

	}

	if(!function_exists("anchor")){
		function anchor($link, $text = NULL, $attrs = NULL){
			if(!isset($text) OR $text === NULL){
				$text = $link;
			}

			if($attrs){
				if(!is_array($attrs)){
					$attrs = array($attrs);
				}
			}else{
				$attrs = array();
			}

			return "<a href='{$link}' ".implode(" ", $attrs).">{$text}</a>";
		}
	}


	// Indexed object array, ignore duplicates
	// If different objects exist for same index, the later one gets priority
	define("UTIL_IOB_IGNORE_DUPLICATES", 0);
	// Whatever be the case, items will be grouped as array
	define("UTIL_IOB_FORCE_ARRAY", 1);

	if(!function_exists("indexed_object_array")){
		function indexed_object_array($haystack, $needle, $flag = UTIL_IOB_FORCE_ARRAY){
			$result_object_array = array();
	        foreach ($haystack as $some_key => $some_object) {
	            $index = $some_object->{$needle};
	            if($flag == UTIL_IOB_IGNORE_DUPLICATES){
	            	$result_object_array["{$index}"] = $some_object;
	            }else{
	            	if(!is_array($result_object_array["{$index}"])){
	            		$result_object_array["{$index}"] = array();
	            	}

	            	$result_object_array["{$index}"][] = $some_object;
	            }
	        }
	        return $result_object_array;
		}
	}


