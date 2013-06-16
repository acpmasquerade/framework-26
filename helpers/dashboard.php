<?php

/**
 * Helper methods for the dashboard
 */
class Helper_Dashboard extends Helper {

	public static function get_announcements($where = array()){
		return self::db()->get(DB::db_tbl_announcements, $where);
	}
}
