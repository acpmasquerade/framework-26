<?php
	class Helper_Report{
		public static function generate_outgoing_statement_grouped_by_user($filters = array()){

			$filters_sql = array();
			$filters_sql[] = " `response_code` = '200'";

			if(isset($filters["username"]) AND $filters["username"]){
				$filter_username_escaped = DB::escape($filters["username"]);
				$filters_sql[] = "`username` = '{$filter_username_escaped}' ";
			}

			if(isset($filters["date-from"]) AND $filters["date-from"]){
				$date_from = DB::escape($filters["date-from"]." 00:00:00");
				$filters_sql[] = "`created` >= '{$date_from}' ";
			}

			if(isset($filters["date-to"]) AND $filters["date-to"]){
				$date_to = DB::escape($filters["date-to"]." 23:59:59");
				$filters_sql[] = "`created` <= '{$date_to}' ";
			}

			$filters_sql_implode = implode(" AND ", $filters_sql);
			if($filters_sql_implode){
				$filters_sql_implode = "WHERE {$filters_sql_implode} ";
			}

			$outgoing_logs_SQL = "SELECT `l`.`username`, sum(`credits`) billable_credits from `outgoing_logs` as `l`
								{$filters_sql_implode}
								group by username ORDER BY username ASC; ";

			$outgoing_logs = DB::select_all($outgoing_logs_SQL);

			$outgoing_logs_grouped_by_username = array();

			foreach($outgoing_logs as $some_log){
				$outgoing_logs_grouped_by_username["{$some_log->username}"] = $some_log;
			}

			if(!isset($filters["username"]) OR !$filters["username"]){

				$all_username = DB::select_all("SELECT `username` from `users` WHERE `status` = '1' AND `subscribed` = 'YES'; ");

				foreach($all_username as $some_user){

					if(!isset($outgoing_logs_grouped_by_username["{$some_user->username}"])){

						$outgoing_log_default_array = new stdClass();
						$outgoing_log_default_array->username = "";
						$outgoing_log_default_array->billable_credits = 0;


						$outgoing_log_default_array->username = $some_user->username;
						$outgoing_logs_grouped_by_username["{$some_user->username}"] = $outgoing_log_default_array;
					}
				}
			}

			return $outgoing_logs_grouped_by_username;
		}

		public static function generate_outgoing_statement_by_user($username, $filters, $count = FALSE){

			$filters_sql = array();
			$filter_username_escaped = DB::escape($username);
			$filters_sql[] = "`username` = '{$filter_username_escaped}' ";

			if(isset($filters["date-from"]) AND $filters["date-from"]){
				$date_from = DB::escape($filters["date-from"]." 00:00:00");
				$filters_sql[] = "`created` >= '{$date_from}' ";
			}

			if(isset($filters["date-to"]) AND $filters["date-to"]){
				$date_to = DB::escape($filters["date-to"]." 23:59:59");
				$filters_sql[] = "`created` <= '{$date_to}' ";
			}

			$filters_sql_implode = implode(" AND ", $filters_sql);
			if($filters_sql_implode){
				$filters_sql_implode = "WHERE {$filters_sql_implode} ";
			}

			if($count === TRUE){
				$max_results_db_call = DB::select("SELECT count(*) TOTAL from `outgoing_logs` {$filters_sql_implode} ");
				$max_results = $max_results_db_call->TOTAL;
				return $max_results;
			}

			$outgoing_logs = DB::select_all("SELECT * from `outgoing_logs` {$filters_sql_implode} ORDER BY created DESC  ", NULL, TRUE);

			return $outgoing_logs;

		}
	}
