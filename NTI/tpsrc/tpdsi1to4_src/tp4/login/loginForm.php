<?php
include_once dirname(__FILE__) . '/urlfunc.inc.php';
?>
<script type="text/javascript" >
function checkForm() {
   if ( document.loginform.login.value.length == 0  )
     {
       alert("Le champ login est obligatoire");
       document.loginform.login.focus();
       return false;
     }
   if ( document.loginform.password.value.length == 0  )
     {
       alert("Le champ mot de passe est obligatoire");
       document.loginform.password.focus();
       return false;
     }
   return true; 
}

</script>

<BR>
Entrez votre login et votre mot de passe :
<FORM  name="loginform"
<?php
	$url = $_SERVER['PHP_SELF'];
	if ( !isset($content) ) $content = '';
   echo "action=\"$url\"";
?>
         onSubmit="return checkForm();"
	 enctype="x-www-form-encoded" method="post">
<?php 
$excludeNames = array('password', 'login', 'logout');
echo getAllRequestParamsAsHiddenParams($excludeNames); 
?>
      <INPUT name="nextURL" type="hidden"  value="<?php echo $nextURL; ?>"> 
      <TABLE border="0" width="481">
        <TBODY>
        <TR>
          <TD>
            <TABLE border="0" cellPadding="0" cellSpacing="0" width="100%">
              <TBODY>
              <TR>
                <TD CLASS="TDFORM" width="92%">
                  <CENTER><FONT size="2">
                      <B>IDENTIFICATION</B></FONT></CENTER></TD>
                </TR></TBODY></TABLE>
            <TABLE border="1" borderColor="#80D0A0" cellSpacing="0" width="100%">
              <TBODY>
              <TR>
                <TD>
                  <TABLE border="0" cellSpacing="0" width="100%">
                    <TBODY>
                    <TR>
                      <TD CLASS="TDFORM" width=189><B>Votre login :</B></TD>
                      <TD CLASS="TDFORM"><INPUT maxLength="15" name="login" size="15" VALUE="<?php echo "$login" ?>" ></TD></TR>
                    <TR>
                      <TD CLASS="TDFORM" width=189><B>Votre mot de passe :</B></TD>
                      <TD CLASS="TDFORM"><INPUT maxLength="8" name="password" size="8" 
                        type="password"> </TD></TR>

<TR><TD CLASS="TDFORM" colspan="2" align="right">
<INPUT TYPE="submit" id="loginSubmit" value="Valider">
</TD></TR>

</TBODY></TABLE>

</TD></TR></TBODY></TABLE>


</TD></TR></TBODY></TABLE>

</FORM> <script type="text/javascript"> document.loginform.login.focus(); </script>

