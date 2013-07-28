<?php

class Helper_General extends Helper{
	
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

    public static function is_valid_url($url){
        if(preg_match("#((http|https)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie", $url)){
            return true;
        }
        return false;
    }

    public static function is_not_a_private_ip($ip_address_or_host){

        $url_parsed = parse_url($ip_address_or_host);
        
        if(isset($url_parsed["host"])){
            $validate = $url_parsed["host"];
        }elseif(isset($url_parsed["path"])){
            $validate = $url_parsed["path"];
        }else{
            return NULL;
        }

        $host_by_name = gethostbyname($validate); 

        if(filter_var($host_by_name, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)){
            return true;
        }else{
            return false;
        }
    }

}