<?php

if ( is_file(dirname(__FILE__) . '/../test.conf.php') )
  include(dirname(__FILE__) . '/../test.conf.php');
else {
  require_once ('simpletest/autorun.php');
	require_once('simpletest/web_tester.php');
}
include_once ('AbstractCommonWebTest.php');

class CreateTicketTests extends AbstractCommonWebTest {
	const TP_NAME = 'tp4';
	const SCRIPT_TO_TEST_NAME = 'editTicket.php';
	const PERSISTANT_FILE_NAME = 'tickets.csv';
	const APP_NAMES_FILE_NAME = 'app.csv';
	private $tpFileRootDir;
	private $appNamesFilePath;
	private $persistantFilePath;

	function setUp() {
		parent::setUp();
		$createTicketURL = $this->getProjectRootUrl() . self::TP_NAME . '/' . self::SCRIPT_TO_TEST_NAME;
		$relativeTpDir =  $this->rootDirName . '/' . self::TP_NAME;
		$this->tpFileRootDir = dirname(dirname(dirname(__FILE__))) . '/' . self::TP_NAME;
		$this->appNamesFilePath = "$this->tpFileRootDir/" . self::APP_NAMES_FILE_NAME;
		$this->createAppNameFile($this->appNamesFilePath);
		// delete persistent tickets 
		$this->persistantFilePath = "$this->tpFileRootDir/" . self::PERSISTANT_FILE_NAME;
		if ( is_file($this->persistantFilePath) ) unlink($this->persistantFilePath);
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

	function notatestgetProjectRootUrl() {
		$createTicketURL = $this->getProjectRootUrl() . 'tp4/editTicket.php';
		$expectedCreateTicketURL = 'http://localhost:80/tpdsi/tp4/editTicket.php';
		$this->assertEqual($expectedCreateTicketURL, $createTicketURL);
	}
	
	function testCreateUrgentTicketOnYahd() {
		// $application = 'default';
		$applicationOther = 'YAHD';
		$oneLiner = 'oneLiner contents';
		$priorityCode = 4; $priorityText = 'Urgente';
		// $type = 'anomalie';
		$contents = 'contents field content';
		$this->fillCreateForm($applicationOther, $priorityCode, $oneLiner, $contents);
		// submit the form
		$this->click('Envoyer');
		
		// http://simpletest.org/en/web_tester_documentation.html
		$this->assertText($applicationOther);
		$this->assertText($priorityText);
		$this->assertText($oneLiner);
		$this->assertText($contents);
		// test color
		// $this->assertPattern('/<tr class="tab_bg_orange">/');
		$this->assertPattern('/<tr .*class=[" \']*tab_bg_orange/');
		// check that a ticket has been persisted
		$this->checkPersistance($applicationOther, $priorityCode, $oneLiner, $contents);
	}
	
	function testCreateUrgentTicketOnPaye() {
		$application = 'Paye';
		$applicationOther = '';
		$oneLiner = 'oneLiner contents on Paye';
		$priorityCode = 3; $priorityText = 'Moyenne';
		// $type = 'anomalie';
		$contents = 'contents field content';
		$this->fillCreateForm($applicationOther, $priorityCode, $oneLiner, $contents, $application);
		// submit the form
		$this->click('Envoyer');
		// http://simpletest.org/en/web_tester_documentation.html
		$this->assertText($application);
		$this->assertText($priorityText);
		$this->assertText($oneLiner);
		$this->assertText($contents);
		// test color
		// $this->assertPattern('/<tr class="tab_bg_yellow">/');
		$this->assertPattern('/<tr .*class=[" \']*tab_bg_yellow/');
	}
	
	function fillCreateForm($applicationOther, $priority, $oneLiner, $contents, $application = -1) {
		$this->setField('applicationOther', $applicationOther);
		// test si le champ existe bien
		$this->assertField('applicationOther', $applicationOther);
		$this->setField('oneLiner', $oneLiner);
		// test si le champ existe bien
		$this->assertField('oneLiner', $oneLiner);
		$this->setField('priority', $priority);
		// test si le champ existe bien
		$this->assertField('priority', $priority);
		$this->setField('contents', $contents);
		// test si le champ existe bien
		$this->assertField('contents', $contents);
		$this->setField('application', $application);
		// test si le champ existe bien
		$this->assertField('application', $application);
	}

	function checkPersistance($applicationOther, $priority, $oneLiner, $contents, $application = -1) {
		$savedTicket = trim(file_get_contents($this->persistantFilePath));
		// 0;YAHD;xxx;4;anomalie;31/01/2008;oneLiner contents;contents 
		list($savedApplicationId, $savedApplicationName, $savedLogin, $savedPriority, $savedType, $savedDate, $savedOneLiner, $savedContents) = explode(';', $savedTicket);
		$this->assertTrue($savedApplicationId >= 0);
		$applicationName = ( $application == -1 ) ? $applicationOther : $application;
		$this->assertEqual($savedApplicationName, $applicationName);
		$this->assertEqual($savedLogin, $this->testLogin);
		$this->assertEqual($savedPriority, $priority);
		$todayDate = date('d/m/Y');
		$this->assertEqual($savedDate, $todayDate);
		$this->assertEqual($savedOneLiner, $oneLiner);
		$this->assertEqual($savedContents, $contents);
	}

	function createAppNameFile($file) {
		$f = fopen($file, 'w');
		$apps = <<<EOT
1;    Paye
2;Inscriptions
3;  Achats
4;Messagerie		
EOT;
		fwrite($f, $apps);
		fclose($f);
	}
	
}

?>
