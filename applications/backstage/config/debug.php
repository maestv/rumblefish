<?php
  require_once('FirePHPCore/FirePHP.class.php');
  $firephp = FirePHP::getInstance(true);
  $firephp-> *
   
  require_once('FirePHPCore/fb.php');
  FB:: *
  
  $firephp->setEnabled(false);
  if ($config['firephp'] == true){
    $firephp->setEnabled(true);  // or FB::
    require_once('FirePHPCore/fb.php');
  }
?>
