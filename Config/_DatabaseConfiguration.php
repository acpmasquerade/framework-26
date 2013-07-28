<?php

	/** 
	 *	DatabaseConfiguration Class
	 *	Framework-26
	 *	info@acpmasquerade.com
	 *	; Declare all the database tables and constants here.
	 */

	class DatabaseConfiguration extends ezSQL_mysql{
		const default_limit = 10;

		const db_tbl_users = "users";
		const db_tbl_users_meta = "users_meta";
		const db_tbl_announcements = "announcements";

	}