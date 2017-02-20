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
	const SCRIPT_TO_TEST_NAME = 'editTicket.php';
	const APP_NAMES_FILE_NAME = 'app.csv';
	private $tpFileRootDir;
	private $appNamesFilePath;

	function setUp() {
		parent::setUp();
		$createTicketURL = $this->getProjectRootUrl() . TP_NAME . '/' . self::SCRIPT_TO_TEST_NAME;
		$relativeTpDir =  $this->rootDirName . '/' . TP_NAME;
		$this->tpFileRootDir = dirname(dirname(dirname(__FILE__))) . '/' . TP_NAME;
		$this->appNamesFilePath = "$this->tpFileRootDir/" . self::APP_NAMES_FILE_NAME;
		$this->createAppNameFile($this->appNamesFilePath);
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
		$createTicketURL = $this->getProjectRootUrl() . 'tp5/editTicket.php';
		$expectedCreateTicketURL = 'http://localhost:80/tpdsi/tp5/editTicket.php';
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
