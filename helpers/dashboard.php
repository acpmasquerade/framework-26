<?php

class Helper_Dashboard extends Helper {

	public static function get_announcements($where = array()){
		return self::db()->get(DB::db_tbl_announcements, $where);
	}


    public static function report_generate_consumption_graph_by_client_id($client_id){
        $client_id_escaped = mysql_real_escape_string($client_id);

        $query = "SELECT 
        			CONCAT(YEAR(`created`), '-', MONTH(`created`)) AS `credit_month`, COUNT(`outgoing_id`) TOTAL
    				FROM `sms_message_outgoing`  WHERE `out_client_id` = '{$client_id_escaped}' GROUP BY `credit_month` ";
        $consumption_graph = self::db()->get_results($query);

        $_return = array();


        if($consumption_graph){
	        foreach($consumption_graph as $c){
	            $some_key = date("M,Y", strtotime($c->credit_month."-15 00:00:00"));
	            $_return["{$some_key}"] = $c->TOTAL;
	        }
        }

        $return->keys = array_keys($_return);
        $return->values = array_values($_return);

        return $return;
    }

    public static function get_outgoing_logs($where = array(), $select = NULL, $limit = NULL, $offset = NULL, $order_by = NULL, $group_by = NULL){
        return self::db()->get(DB::db_tbl_sms_message_outgoing, $where, $select, $limit, $offset, $order_by, $group_by);
    }

    public static function get_access_logs($where, $select = NULL, $limit = NULL, $offset = NULL, $order_by = NULL, $group_by = NULL ){
        return self::db()->get(DB::db_tbl_access_logs, $where, $select, $limit, $offset, $order_by, $group_by);
    }

    public static function get_number_of_access_logs($where){
        $access_logs = self::get_access_logs($where);

        if(!$access_logs){
            return 0;
        } else {
            return count($access_logs);
        }
    }

    public function get_number_of_outgoing_logs($where){
        $outgoing_logs = self::get_outgoing_logs($where);

        if(!$outgoing_logs){
            return 0;
        } else {
            return count($outgoing_logs);
        }
    }

}
