<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FirePHPLoader {

  public $logger = Null;
  
  public function load()
  {
    $logger = load_zend();
    $firephp = load_firephp();
    return array($logger, $firephp);
  }
  
  // Load the zend controller.
  // http://framework.zend.com/manual/1.12/en/zend.log.writers.html#zend.log.writers.firebug
  public function load_zend(){
    $writer = new Zend_Log_Writer_Firebug();
    $logger = new Zend_Log($writer);
    return $logger;
  }
  
  // Load Firebug.
  // http://www.firephp.org/HQ/Use.html
  public function load_firephp(){
    require_once('FirePHPCore/FirePHP.class.php');
    $firephp = FirePHP::getInstance(true);
    //$firephp-> *
     
    require_once('FirePHPCore/fb.php');
    //FB:: *
     
    $firephp->setEnabled(true);  // or FB::
     
    FB::send(/* See fb() */);
    return $firephp;
  }
}

/* End of file Someclass.php */
