<?php
include_once dirname(__FILE__) . '/urlfunc.inc.php';
?>

<?php
$login = $password = '';
if ( isset($_REQUEST['login']) )
  $login = $_REQUEST['login'];
if ( isset($_REQUEST['password']) )
  $password = $_REQUEST['password'];
if ( !isset($nextURL) ) $nextURL = '';
$nextURL = getHttpParameter('nextURL', $nextURL);

if ( $login != '' )
{
  $res = $loginControl->isValidLogin($login); // $loginControl object should be in the scope
  if ( $res )
    {
      $res = $loginControl->isValidPassword($login, $password);
      if ( !$res )
        {
          echo "<center><BR><font color=\"#FF0000\" size=\"+1\">Mot de passe invalide.</font></center>";
        }
      else
        {
	  $_SESSION['userId'] = $login;
	  if ( $nextURL == '' )
	    echo "Bienvenue $login (***TBD*** vérif)";
	  else
	    {
	      $excludeNames = array('password', 'login', 'logout');
          $nextURL = appendPostParamsToURL($nextURL, $excludeNames);
	      redirectLocation($nextURL);
	    }
	  // exit();
        }
    }
  else
    {
      echo "<center><BR><font color=\"#FF0000\" size=\"+1\">Login $login inconnu.</font></center>";
    }
}
?>

      <CENTER>

<?php
   include dirname(__FILE__) . '/loginForm.php';
?>

<BR>


</CENTER>

