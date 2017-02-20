<?php                                                                        
include_once dirname(__FILE__) . '/AbstractLoginController.class.php';

class AcceptAllExceptZZZLoginController extends AbstractLoginController {

  function isValidLogin($login)
  {
  	if ( $login == 'ZZZ' ) return false;
    return true;
  }

  function isValidPassword($login, $password)
  {
    return true;
  }
}
?>
