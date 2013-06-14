<?php

class Helper_General {
	
	public static $networks = array("ntc", "ncell", "utl", "smart");

	public static $shortcodes = array("5001", "4001", "3001", "2500", "6001");

	const default_client_id = "demo";
	const default_credits = "100";

	const default_credit_topup_amount = "5";
	const host = "http://www.sparrowsms.com";

	public static function get_default_networks(){
		return implode(",", self::$networks);
	}

	public static function get_default_shortcodes(){
		$_default_shortcodes = array();

		foreach (self::$networks as $some_network) {
			$_default_shortcodes["{$some_network}"] = self::$shortcodes;
		}

		return json_encode($_default_shortcodes);
	}

	/**
     * 
     * @param type $details
     * @param type $template
     * @param array $template_params
     * @return boolean
     */
    public static function send_email($details, $template_params = array()) {

        $template_params["site_url"] = SITE_URL;
        $mail_type = "text/html";

        if (count($details) > 0) {
            if (!isset($details["to"])) {
                return FALSE;
            } else {
                $to = $details["to"];
            }

            if (!isset($details["from_name"])) {
                $from_name = DEFAULT_EMAIL_NAME;
            } else {
                $from_name = $details["from_name"];
            }

            if (!isset($details["from_email"])) {
                $from_email = DEFAULT_EMAIL;
            } else {
                $from_email = $details["from_email"];
            }

            
            if (!isset($details["message"])) {
                $message = "";
            } else {
                $message = $details["message"];
            }

            if (!isset($details["subject"])) {
                $subject = "[No subject]";
            } else {
                $subject = $details["subject"];
            }

            $message = nl2br($message);

			mail($to,$subject,$message,"From: {$from_email}\r\nContent-Type: {$mail_type}\r\n");
        }
    }

}