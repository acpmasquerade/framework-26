<?php
	class Helper_Template extends Helper{
		public static function page_not_found(){
			Config::set("header", "");
			Template::set("404", array());
			return ;
		}

		public static function forbidden(){
			Config::set("header", "");
			Template::set("403", array());
			return ;
		}
	}