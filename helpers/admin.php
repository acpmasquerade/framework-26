<?php

class Helper_Admin extends Helper {

	public static function get_client_config($where = array()){
		$client_config = self::get_client_configs($where);

		if($client_config){
			if(is_array($client_config) && count($client_config) > 0){
				return $client_config[0];
			}
		}
		return false;
	}

	public static function get_client_configs($where = array()){
		return self::db()->get(DB::db_tbl_client_config, $where);
	}

}