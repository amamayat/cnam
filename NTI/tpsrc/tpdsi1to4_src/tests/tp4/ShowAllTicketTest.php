<?php

if ( is_file(dirname(__FILE__) . '/../test.conf.php') )
  include(dirname(__FILE__) . '/../test.conf.php');
else {
  require_once ('simpletest/autorun.php');
	require_once('simpletest/web_tester.php');
}
include_once ('AbstractCommonWebTest.php');

class ShowAllTicketTest extends AbstractCommonWebTest {
	const TP_NAME = 'tp4';
	const SCRIPT_TO_TEST_NAME = 'showAllTickets.php';
	const PERSISTANT_FILE_NAME = 'tickets.csv';
	private $tpFileRootDir;
	private $persistantFilePath;

	function setUp() {
		parent::setUp();
		$showTicketURL = $this->getProjectRootUrl() . self::TP_NAME . '/' . self::SCRIPT_TO_TEST_NAME;
		$relativeTpDir =  $this->rootDirName . '/' . self::TP_NAME;
		$this->tpFileRootDir = dirname(dirname(dirname(__FILE__))) . '/' . self::TP_NAME;
		// delete persistent tickets 
		$this->persistantFilePath = "$this->tpFileRootDir/" . self::PERSISTANT_FILE_NAME;
		$this->deleteAllTickets();
		$this->createInitialTickets($this->persistantFilePath);
		$this->get($showTicketURL);
		// login with a valid login
        $this->submitLoginForm($this->testLogin, $this->testPassword);
		// sleep(10); second call mandatory!! We do not understand why!!
		$this->submitLoginForm($this->testLogin, $this->testPassword);
    }

	function tearDown() {
		// deconnect
		$this->clickLinkById('deconnexion');
		// $this->deleteAllTickets();
	}

	function notatestgetProjectRootUrl() {
		$showTicketURL = $this->getProjectRootUrl() . self::TP_NAME . '/' . self::SCRIPT_TO_TEST_NAME;
		$expectedShowTicketURL = 'http://localhost:80/tpdsi/tp4/showAllTickets.php';
		$this->assertEqual($expectedShowTicketURL, $showTicketURL);
	}
	
	function testUrgentTicketOnYahd() {
		// $application = 'default';
		$applicationOther = 'YAHD';
		$oneLiner = 'oneLiner 1';
		$priorityCode = 4; $priorityText = 'Urgente';
		// $type = 'anomalie';
		$detailedDescription = 'detailed description 1';

		$this->assertText($applicationOther);
		$this->assertText($priorityText);
		$this->assertText($oneLiner);
		$this->assertText($detailedDescription);
		// test color
		// $this->assertPattern('/<tr class="tab_bg_orange">/');
		$this->assertPattern('/<tr .*class=[" \']*tab_bg_orange/');
	}
	
	function testTicketOnPaye() {
		$application = 'Paye';
		$oneLiner = 'oneLiner 2';
		$priorityCode = 3; $priorityText = 'Moyenne';
		// $type = 'anomalie';
		$detailedDescription = 'detailed description 2';
		$this->assertText($application);
		$this->assertText($priorityText);
		$this->assertText($oneLiner);
		$this->assertText($detailedDescription);
		// test color
		// $this->assertPattern('/<tr class="tab_bg_yellow">/');
		$this->assertPattern('/<tr .*class=[" \']*tab_bg_yellow/');
	}

	function createInitialTickets($file) {
		$f = fopen($file, 'w');
		$tickets = <<<EOT
1;Paye;unittest;3;anomalie;06/02/2008;oneLiner 1;detailed description 1;
2;Achats;unittest;5;anomalie;06/02/2008;oneLiner 2;detailed description 2;
3;YAHD;unittest;4;anomalie;06/02/2008;oneLiner 2;detailed description 2;

EOT;
		fwrite($f, $tickets);
		fclose($f);
	}

	function deleteAllTickets() {
		// delete file
		@unlink($this->persistantFilePath);
	}
	
}

?>
