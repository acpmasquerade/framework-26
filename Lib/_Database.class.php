<?php
	
require_once __DIR__."/_ezsql.core.class.php";
require_once __DIR__."/_ezsql.class.php";

class DB extends ezSQL_mysql {

	const default_limit = 10;

	const db_tbl_client_credits = "client_credits";
	const db_tbl_client_config = "client_config";
	const db_tbl_client_credits_meta = "client_credits_meta";
	const db_tbl_client_credit_history = "client_credit_history";
	const db_tbl_announcements = "announcements";
	const db_tbl_sms_message_outgoing = "sms_message_outgoing";
	const db_tbl_access_logs = "access_logs";

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
            $query = "SELECT {$select} FROM {$table_name} ";
        } else {
            $query = "SELECT * FROM {$table_name} ";
        }


        if ($where) {
            $query .= $this->_where($where);
        }

        if($limit){
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

                if ($counter === 1) {
                    $query .= " WHERE `{$where_condition_key}` = '{$where_condition_value}'";
                } else {
                    $query .= " AND `{$where_condition_key}` = '{$where_condition_value}'";
                }
            }
        } else {
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

		$this->query($query);

		if(mysql_errno())
			return false;
		else
			return true;
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

		if(mysql_error())
			return false;

		// $resultset = array();
		// while($object = mysql_fetch_object($res)){
		// 	$resultset[] = $object;
		// }

		// return $resultset;

		return $res;
	}

	// public function select($query){
	// 	$res = $this->execute_query($query);

	// 	if(mysql_error()){
	// 		return false;
	// 	}

	// 	$object = mysql_fetch_object($res);

	// 	return $object;
	// }

}