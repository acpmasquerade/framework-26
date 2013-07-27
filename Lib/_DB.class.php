<?php
	
	/**
	 *	The original database class for Framework-26
	 *	info@acpmasquerade.com
	 *	Framework-26
	 **/
	class DB {
		private static $last_query;

		const default_limit = 10;

		private static function set_query($query){
			self::$last_query = $query;
		}

		private static function get_query(){
			return self::$last_query;
		}

		public static function last_query(){
			return self::get_query();
		}

		public static function escape($string){
			return mysql_real_escape_string($string);
		}

		public static function insert($table, $values){
			$query = "";

			foreach($values as $key=>$val){
				$values[$key] = mysql_real_escape_string($val);
			}

			$keys = array_keys($values);

			$query = "INSERT INTO {$table} ";
			$query .= "(" . "`".implode("`,`", $keys)."`" .")";
			$query .= "VALUES(" . "'".implode("','", $values)."'" .")";

			self::execute_query($query);

			if(mysql_errno())
				return false;
			else
				return true;
		}

		public static function error(){
			return array("code"=>mysql_errno(), "message" => mysql_error());
		}

		public static function update($table, $values, $where){

			if(!$values){
				return false;
			}

			$query = "";

			$keys = array_keys($values);

			$query = "UPDATE `{$table}` ";

			$update_query = array();
			foreach($values as $key=>$val){
				$update_query[] = "`".mysql_real_escape_string($key)."`". " = ". "'".mysql_real_escape_string($val)."'";
			}

			$query .= "SET ";
			$query .= implode(", ", $update_query);

			if(is_array($where) OR is_object($where)){
				foreach($where as $key=>$val){
					$where_query[] = "`".mysql_real_escape_string($key)."`". " = '".mysql_real_escape_string($val)."'";
				}
				$where_query = implode(" AND ", $where_query);
			}else{
				$where_query = $where;
			}

			$query .= " WHERE ".$where_query;

			self::execute_query($query);

			if(mysql_errno()){
				return false;
			}else{
				return true;
			}
		}

		public static function execute_query($query){
			
			$res = mysql_query($query);
			
			self::set_query($query);
			
			if(mysql_error()){
				return false;
			}else{
				return $res;
			}
		}

		public static function affected_rows(){
			return mysql_affected_rows();
		}

		public static function select_all($query, $assoc = FALSE, $auto_paginate = FALSE){

			if($auto_paginate === TRUE){
				// build pagination query block from GET arguments
				if(isset($_GET["page_number"])){
					$page_number = intval($_GET['page_number']);
					if($page_number <= 0 ){
						$page_number = 1;
					}
				}else{
					$page_number = 1;
				}

				$limit = ( isset($_GET["limit"]) AND intval($_GET["limit"]) > 0 ) ? $_GET["limit"] : DB::default_limit;

				$pagination_offset = ( $page_number - 1 ) * $limit;
				$pagination_block = "LIMIT {$pagination_offset}, {$limit} ";
			}else{
				$pagination_block = "";
			}

			$res = self::execute_query("{$query} {$pagination_block} ");

			if(mysql_error())
				return false;

			$resultset = array();
			while($object = mysql_fetch_object($res)){
				$resultset[] = $object;
			}

			return $resultset;
		}

		public static function select($query){
			$res = self::execute_query($query);

			if(mysql_error()){
				return false;
			}

			$object = mysql_fetch_object($res);

			return $object;
		}

		public static function connect($host, $username, $password, $database){
			mysql_connect($host, $username, $password);
			mysql_select_db($database);
		}

		public static function close(){
			mysql_close();
			return;
		}
	}