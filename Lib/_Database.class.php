<?php

/**
 * 	@author : cooshal, acpmasquerade@gmail.com
 *  @package : Framework-26
 */

/**
 *	The Debugger Class for Database
 *	info@acpmasquerade.com
 *	Framework-26
 */
class DBDebug{
	public static function dump($file, $log_array){
		$realpath = realpath(__DIR__."/../Logs/{$file}");
		
		if(!$realpath){
			$realpath = realpath(__DIR__.'/../Logs/debug.log');
		}

		ob_start();
		print_r($log_array);
		$log_text = ob_get_contents();
		ob_end_clean();

		file_put_contents
		(
			realpath($realpath), 
			$log_text."\n=========\n", 
			FILE_APPEND
		);

		if(DebugBar::is_enabled() === true){
			DebugBar::append($file, $log_array);
		}

		return true;
	}
}

class DB extends DatabaseConfiguration {

	public function insert($table, $values){
		$query = "";

		foreach($values as $key=>$val){
			$values[$key] = mysql_real_escape_string($val);
		}

		$keys = array_keys($values);

		$query = "INSERT INTO {$table} ";
		$query .= "(" . "`".implode("`,`", $keys)."`" .")";
		$query .= "VALUES(" . "'".implode("','", $values)."'" .")";

		$this->execute_query($query);

		if(mysql_errno()) {
			return false;
		}
		
		return mysql_insert_id();

	}


	 /**
     *
     * @param string $table_name
     * @param array $where
     * @param string $select
     * @param int $limit
     * @param int $offset
     * @param string $order_by
     * @param string $group_by
     * @return array
     */
    public function get($table_name = NULL, $where = array(), $select = NULL, $limit = NULL, $offset = NULL, $order_by = NULL, $group_by = NULL) {
        if (!$table_name) {
            return FALSE;
        }

        if ($select) {

        	if(is_array($select) OR is_object($select)){
        		$select = "`".implode("`,`" , $select)."`";
        	}

            $query = "SELECT {$select} FROM `{$table_name}` ";
        } else {
            $query = "SELECT * FROM `{$table_name}` ";
        }


        if ($where) {
            $query .= $this->_where($where);
        }

        if($group_by){
			$group_by = mysql_real_escape_string($group_by)        	;
            $query .= " GROUP BY {$group_by}";
        }

        if($order_by){
        	$order_by = mysql_real_escape_string($order_by)        	;
            $query .= " ORDER BY {$order_by}";
        }

        if($limit){
        	$limit = intval($limit);
            if(!$offset){
                $offset = 0;
            }
           $query .= " LIMIT {$offset}, {$limit}";
        }

        return $this->get_results($query);
    }

    private function _where($where = array()) {
        $query = "";
        $counter = 0;
        if (is_array($where)) {
            foreach ($where as $where_condition_key => $where_condition_value) {
                $counter++;

                $where_condition_key = mysql_real_escape_string($where_condition_key);
                $where_condition_value = mysql_real_escape_string($where_condition_value);

                if ($counter === 1) {
                    $query .= " WHERE `{$where_condition_key}` = '{$where_condition_value}'";
                } else {
                    $query .= " AND `{$where_condition_key}` = '{$where_condition_value}'";
                }
            }
        } else {
        	// It is never recommended to send a direct query string unless you are sure that the string is escaped.
        	// There won't be escaping in case of string WHERE condition.
        	if(Config::get("db_saftey_debug") === true){
        		$where_call_log = array("date"=>date('r'), "where"=>$where, "backtrace"=>debug_backtrace());
        		DBDebug::dump("unescaped-db-where.log", $where_call_log);
        	}        	
            $query .= " WHERE {$where}";
        }
        return $query;
    }

	public function update($table, $values, $where){

		if(!$values){
			return false;
		}

		$query = "";

		$keys = array_keys($values);

		$table_escaped = mysql_real_escape_string($table);

		$query = "UPDATE `{$table_escaped}` ";

		$update_query = array();

		foreach($values as $key=>$val){
			$key_escaped = mysql_real_escape_string($key);
			$val_escaped = mysql_real_escape_string($val);
			$update_query[] = " `{$key_escaped}` = '{$val_escaped}' ";
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

		$this->query($query);

		if( mysql_errno() ){
			return false;
		}
		else {
			return true;
		}
	}

	public function delete($table, $where = array()){
		if( !$table ) {
			return false;
		}

		$table = $this->escape($table);

		// build the delete query
		$query = "DELETE FROM `{$table}` ";

		if($where){
			$where = $this->_where($where);

			$query .= $where;
		}

		// after building the query, execute it
		$this->query($query);

		if( mysql_errno() ){
			return false;
		}
		else {
			return $this->affected_rows();
		}
	}

	public function execute_query($query){
		$this->get_results($query);
	}

	public function affected_rows(){
		return mysql_affected_rows();
	}

	public function select_all($query, $assoc = FALSE, $auto_paginate = FALSE){

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

		$res = $this->execute_query("{$query} {$pagination_block} ");

		if( mysql_error() ) {
			return false;
		}

		return $res;
	}

	public function begin_transaction(){
		$this->query("SET AUTOCOMMIT=0");
		$this->query("START TRANSACTION");
	}

	public function commit(){
		$this->query("COMMIT");
	}

	public function rollback(){
		$this->query("ROLLBACK");
	}
}