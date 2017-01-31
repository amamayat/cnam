<?php

if ( is_file(dirname(__FILE__) . '/../test.conf.php') )
  include(dirname(__FILE__) . '/../test.conf.php');
else {
  require_once ('simpletest/autorun.php');
	require_once('simpletest/web_tester.php');
}
include_once ('AbstractCommonWebTest.php');
include (dirname(__FILE__) . '/thistest.conf.php');

class CreateTicketTests extends AbstractCommonWebTest {
	const SCRIPT_TO_TEST_NAME = '?content=view/editTicket.inc.php';

	function setUp() {
		parent::setUp();
		$createTicketURL = $this->getProjectRootUrl() . TP_NAME . '/' . self::SCRIPT_TO_TEST_NAME;
		$relativeTpDir =  $this->rootDirName . '/' . TP_NAME;
		$this->get($createTicketURL);
		// login with a valid login
        $this->submitLoginForm($this->testLogin, $this->testPassword);
		// sleep(10); second call mandatory!! We do not understand why!!
		$this->submitLoginForm($this->testLogin, $this->testPassword);
    }

	function tearDown() {
		// deconnect
		$this->clickLinkById('deconnexion');
	}
	
	function testCreateUrgentTicketOnMessagerie() {
		$productId = 2;
		$oneLiner = 'oneLiner contents';
		$priorityCode = 4; $priorityText = 'Urgente';
		// $type = 'anomalie';
		$contents = 'contents field content';
		$this->fillCreateForm($productId, $priorityCode, $oneLiner, $contents);
		// submit the form
		$this->click('Envoyer');
		
		// http://simpletest.org/en/web_tester_documentation.html
		$this->assertText('Messagerie');
		$this->assertText($priorityText);
		$this->assertText($oneLiner);
		$this->assertText($contents);
		// test color
		// $this->assertPattern('/<tr class="tab_bg_orange">/');
		$this->assertPattern('/<tr .*class=[" \']*tab_bg_orange/');
	}
	
	function testCreateUrgentTicketOnPaye() {
		$productId = 1;
		$oneLiner = 'oneLiner contents on Paye';
		$priorityCode = 3; $priorityText = 'Moyenne';
		// $type = 'anomalie';
		$contents = 'contents field content';
		$this->fillCreateForm('1', $priorityCode, $oneLiner, $contents);
		// submit the form
		$this->click('Envoyer');
		// http://simpletest.org/en/web_tester_documentation.html
		$this->assertText('Paye');
		$this->assertText($priorityText);
		$this->assertText($oneLiner);
		$this->assertText($contents);
		// test color
		// $this->assertPattern('/<tr class="tab_bg_yellow">/');
		$this->assertPattern('/<tr .*class=[" \']*tab_bg_yellow/');
	}
	
	function fillCreateForm($productId, $priority, $oneLiner, $contents) {
		$this->setField('productId', $productId);
		// test si le champ existe bien
		$this->assertField('productId', $productId);
		$this->setField('oneLiner', $oneLiner);
		// test si le champ existe bien
		$this->assertField('oneLiner', $oneLiner);
		$this->setField('priority', $priority);
		// test si le champ existe bien
		$this->assertField('priority', $priority);
		$this->setField('contents', $contents);
		// test si le champ existe bien
		$this->assertField('contents', $contents);
	}

}

?>
