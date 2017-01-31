<?php  

// initialiser le controleur de login
include_once dirname(__FILE__) . '/loginAccess.conf.php';

$logout = 0;
if ( isset($_REQUEST['logout']) )
  $logout = $_REQUEST['logout'];
if ( $logout == 1 )
{
  session_destroy();
}   

if ( isset($_SESSION['userId']) && $logout != 1 )
{
  $userId = $_SESSION['userId'];
}
else
{
  include_once dirname(__FILE__) . '/urlfunc.inc.php';
  $nextURL = getCurrentScriptFullPath();
  include_once dirname(__FILE__) . '/loginControl.php';
  exit();
}

?>
