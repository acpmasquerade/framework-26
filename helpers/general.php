<?php

class Helper_General {
	
	const host = "acpmasquerade.github.io";
    const default_user_account_status = "active";

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