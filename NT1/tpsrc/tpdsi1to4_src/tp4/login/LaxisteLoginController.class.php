<?php                                                                        
include_once dirname(__FILE__) . '/AbstractLoginController.class.php';

class LaxisteLoginController extends AbstractLoginController {

  function isValidLogin($login)
  {
    return strlen($login >= 2 );
  }

  function isValidPassword($login, $password)
  {
    return $login == $password;
  }
}
?>
