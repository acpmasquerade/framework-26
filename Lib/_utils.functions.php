<?php
	if(!function_exists("redirect")){
		function redirect($url){
			// check notifications
			if(Template::has_notifications()){
				Template::persist_notifications();
			}
			header("Location: {$url}");
			die();
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
			if(!$text){
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