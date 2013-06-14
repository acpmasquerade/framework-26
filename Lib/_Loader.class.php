<?php

	/** 
	 * The Loader class
	 * @author : acpmasquerade@gmail.com
	 * @package : Framework26
	 */
	
	class Loader{
		public static function helper($name){
			$file_path = realpath(__DIR__."/../helpers/{$name}.php");
			if($file_path){
				include_once $file_path;
			}else{
				return false;
			}
		}
	}
