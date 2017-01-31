<?php
// Changer ces 2 lignes pour utiliser un validateur de login différent
include_once dirname(__FILE__) . '/AcceptAllExceptZZZLoginController.class.php';
$loginControl = new AcceptAllExceptZZZLoginController();
?>
