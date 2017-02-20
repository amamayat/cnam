<?php

if ( is_file(dirname(__FILE__) . '/../test.conf.php') )
include(dirname(__FILE__) . '/../test.conf.php');
else {
  require_once ('simpletest/autorun.php');
}
$targetDir = '../../tp4';
include_once "$targetDir/login/LaxisteLoginController.class.php";

class LaxisteLoginControllerTest extends UnitTestCase {
	private /* LaxisteLoginController */ $_laxisteLC;

	function setUp() {
		$this->_laxisteLC = new LaxisteLoginController();		
	}

	function testisValidLogin() {
		$this->assertTrue($this->_laxisteLC->isValidLogin('123'));
		$this->assertTrue($this->_laxisteLC->isValidLogin('12'));
		$this->assertFalse($this->_laxisteLC->isValidLogin('1'));
	}

	function testisValidPassword() {
		$this->assertTrue($this->_laxisteLC->isValidPassword('123', '123'));
		$this->assertFalse($this->_laxisteLC->isValidPassword('123', '321'));
	}
	
}

?>
