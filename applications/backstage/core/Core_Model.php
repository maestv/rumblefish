<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Core_Model extends CI_Model
{
	function __construct()
    {
		parent::__construct();
	}
	
	public function loggedIn()
	{
		return $this->users_model->is_logged_in();
	}
	
	public function api_call($args = null, $kw)
    {
        $url = $args['url'];
        $apiKey = $args['api_key'];
        $args = (array) $args;

        $ch = curl_init();
        $encoded = http_build_query($args, null, '&');

        curl_setopt($ch, CURLOPT_POSTFIELDS, $encoded);

		switch($kw) {
			case "POST":
				curl_setopt($ch, CURLOPT_POST, true);
			break;
			case "PUT":
				curl_setopt($ch, CURLOPT_PUT, true);
			break;
			case "GET":
				curl_setopt($ch, CURLOPT_HTTPGET, true);
			break;
			case "DELETE":
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			break;	
		}

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		print_r($ch);

        $response = curl_exec($ch);
        $json = json_decode($response);

        curl_close($ch);

        if ($json === null) {	
            throw new Exception('The result was not in JSON format.', 1);
        }

        return $json;
    }
}
