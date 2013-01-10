<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class XmlIngestion_Helper
{
	function __construct($array)
	{
		$this->xml = new SimpleXMLElement('<root/>');
		$this->array = $this->object_to_array($array);

		// function call to convert array to xml
		$this->array_to_xml($this->array, $this->xml);
	}
	
	public function save() {
		// Set up FTP
		$host = 'ftp.yourmom.com';
		$user = 'user';
		$password = 'password';

		// Create File to Move.
		$xmlDoc = $this->xml->asXML();
		$filename = md5($xmlDoc).'.xml';
		$fh = fopen(APPPATH.'tmp/'.$filename, "w"); 
			fwrite($fh, $xmlDoc); 
		fclose($fh);
		
		$local_file = APPPATH.'tmp/'.$filename;
		$ftp_path = '/'.$filename;
		
		// connect to FTP server (port 21)
		$conn_id = ftp_connect($host, 21) or die ("Cannot connect to host");
		ftp_login($conn_id, $usr, $pwd) or die("Cannot login");

		// turn on passive mode transfers (some servers need this)
		ftp_pasv ($conn_id, true);

		// perform file upload
		$upload = ftp_put($conn_id, $ftp_path, $local_file, FTP_ASCII); //should return a bool
		ftp_close($conn_id);
		
		if ( $upload ) {
			unlink(APPPATH.'tmp/'.$filename);
		}
		
		return $upload; //should be a bool
	}
	
	public function get() {
		return $this->xml;
	}
	
	protected function object_to_array($obj) 
	{
	    $arrObj = is_object($obj) ? get_object_vars($obj) : $obj;
	    foreach ($arrObj as $key => $val) {
	            $val = (is_array($val) || is_object($val)) ? $this->object_to_array($val) : $val;
	            $arr[$key] = $val;
	    }
	    return $arr;
	}
	
	// Reverse Values because this XML thing is weird and will reverse them again
	private function array_to_xml($array, &$xml) {
	    foreach($array as $key => $value) {
	        if(is_array($value)) {
	            if(!is_numeric($key)){
	                $subnode = $xml->addChild("$key");
	                $this->array_to_xml($value, $subnode);
	            }
	            else{
	                $this->array_to_xml($value, $xml);
	            }
	        }
	        else {
	            $xml->addChild("$key","$value");
	        }
	    }
	}
}
