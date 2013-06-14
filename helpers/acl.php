<?php

Loader::helper("user");

class Helper_ACL {

	// configure components or menus to user roles, statically

	private static $user_role = array(
		"superuser" => "111",
		"admin" => "101",
		"moderator" => "100",
		"reseller" => "11",
		"user" => "10"
	);

	public static function has_higher_privileges($user_role_compare){

		$user_role_base = Helper_User::get_current_user_role();

		if(Helper_User::is_super_user()){
			return true;
		} else {
			if(self::$user_role["{$user_role_base}"] > self::$user_role["{$user_role_compare}"]){
				return true;
			}
		}

		return false;
	}

	public static function get_quick_links($user_role = NULL){
		if(!$user_role){
			$user_role = Helper_User::get_current_user_role();
		}
		
		$menus["admin"] = array(
			"users",
			"config",
			"logs",
			"credits"
		);

		$menus["reseller"] = array(
			"users",
			"logs",
			"credits"
		);

		$menus["user"] = array(
			"users",
			"logs"
		);

		return $menus["{$user_role}"];
	}

	public static function get_link_desc($link){
		$link_icons = array(
			"users" => array("icon" => "user", "display" => "Users", "url" => "user"),
			"config" => array("icon" => "wrench", "display" => "Configuration", "url" => "admin/config"),
			"credits" => array("icon" => "briefcase", "display" => "Credits", "url" => "account/credits"),
			"logs" => array("icon" => "list-alt", "display" => "Logs", "url" => "admin/logs"),
		);

		return $link_icons["{$link}"];
	}
}

