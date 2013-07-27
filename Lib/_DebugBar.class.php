<?php
	/**
	 *	DebugBar
	 *	info@acpmasquerade.com
	 *	Framework-26
	 */

	class DebugBar{
		
		private static $debug_bar_dump;
		private static $is_enabled;

		public static function is_enabled(){
			if(!isset(self::$is_enabled)){
				self::$is_enabled = Config::get("debug_bar");
			}

			return self::$is_enabled;
		}

		public static function append($scope, $details){
			if(!isset(self::$debug_bar_dump)){
				self::$debug_bar_dump = array();
			}

			if(!isset(self::$debug_bar_dump["{$scope}"])){
				self::$debug_bar_dump["{$scope}"] = array();
			}

			self::$debug_bar_dump["{$scope}"][] = $details;
		}

		public static function dump($scope = NULL){
			if(isset($scope)){
				self::print_r_tree(self::$debug_bar_dump["{$scope}"]);
			}else{
				self::print_r_tree(self::$debug_bar_dump);
			}
		}

		public static function get($scope = NULL){
			if(isset($scope)){
				return self::$debug_bar_dump["{$scope}"];
			}

			return self::$debug_bar_dump;
		}

		public static function print_r_tree($data)
		{
			if(Config::get("kint_debug") === true){
				Kint::dump($data);
				return;
			}

			echo "<pre>";

		    // capture the output of print_r
		    $out = print_r($data, true);

		    // replace something like '[element] => <newline> (' with <a href="javascript:toggleDisplay('...');">...</a><div id="..." style="display: none;">
		    $out = preg_replace('/([ \t]*)(\[[^\]]+\][ \t]*\=\>[ \t]*[a-z0-9 \t_]+)\n[ \t]*\(/iUe',"'\\1<a href=\"javascript:toggleDisplay(\''.(\$id = substr(md5(rand().'\\0'), 0, 7)).'\');\">\\2</a><div id=\"'.\$id.'\" style=\"display: none;\">'", $out);

		    // replace ')' on its own on a new line (surrounded by whitespace is ok) with '</div>
		    $out = preg_replace('/^\s*\)\s*$/m', '</div>', $out);

		    echo "</pre>";

		    // print the javascript function toggleDisplay() and then the transformed output
		    echo '<script language="Javascript">function toggleDisplay(id) { document.getElementById(id).style.display = (document.getElementById(id).style.display == "block") ? "none" : "block"; }</script>'."\n$out";
		}
	}