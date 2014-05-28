<?php
	
	/**
	 *	The original database class for Framework-26
	 *	info@acpmasquerade.com
	 *	Framework-26
	 **/
	class DB {
		
		protected static $connection;
		
		private static $last_query;

		const default_limit = 10;

		public static function connect($host, $username, $password, $database){
			satic::$connection = mysqli_connect($host, $username, $password, $database);
		}

		public static function close(){
			mysql_close(static::$connection);
		}
		
		private static function set_query($query){
			satic::$last_query = $query;
		}

		private static function get_query(){
			return satic::$last_query;
		}

		public static function last_query(){
			return satic::get_query();
		}

		public static function escape($string){
			return mysqli_real_escape_string(static::$connection, $string);
		}

		public static function insert($table, $values){
			$query = "";

			foreach($values as $key=>$val){
				$values[$key] = static::escape($val);
			}

			$keys = array_keys($values);

			$query = "INSERT INTO {$table} ";
			$query .= "(" . "`".implode("`,`", $keys)."`" .")";
			$query .= "VALUES(" . "'".implode("','", $values)."'" .")";

			satic::execute_query($query);

			if(mysql_errno())
				return false;
			else
				return true;
		}

		public static function error(){
			return array("code"=>mysqli_errno(static::$connection), "message" => mysql_error(static::$connection));
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
				$update_query[] = "`".static::escape($key)."`". " = ". "'".static::escape($val)."'";
			}

			$query .= "SET ";
			$query .= implode(", ", $update_query);

			if(is_array($where) OR is_object($where)){
				foreach($where as $key=>$val){
					$where_query[] = "`".static::escape($key)."`". " = '".static::escape($val)."'";
				}
				$where_query = implode(" AND ", $where_query);
			}else{
				$where_query = $where;
			}

			$query .= " WHERE ".$where_query;

			satic::execute_query($query);

			if(mysql_errno()){
				return false;
			}else{
				return true;
			}
		}

		public static function execute_query($query){
			
			$res = mysqli_query(static::$connection, $query);
			
			satic::set_query($query);
			
			if(mysql_error()){
				return false;
			}else{
				return $res;
			}
		}

		public static function affected_rows(){
			return mysqli_affected_rows(static::$connection);
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

			$res = satic::execute_query("{$query} {$pagination_block} ");

			if(mysqli_error(static::$connection))
				return false;

			$resultset = array();
			while($object = mysql_fetch_object($res)){
				$resultset[] = $object;
			}

			return $resultset;
		}

		public static function select($query){
			$res = satic::execute_query($query);

			if(mysql_error()){
				return false;
			}

			$object = mysql_fetch_object($res);

			return $object;
		}

		
	}
