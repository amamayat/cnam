<?php
if ( is_file(dirname(__FILE__) . '/../test.conf.php') )
	include(dirname(__FILE__) . '/../test.conf.php');
else {
  require_once ('simpletest/autorun.php');
	require_once('simpletest/web_tester.php');
}
include_once ('AbstractCommonWebTest.php');

class CreateTicketTests extends AbstractCommonWebTest {

	function setUp() {
		$createTicketURL = $this->getProjectRootUrl() . 'tp2/saisieTicket.html';
		parent::setUp();
		$this->get($createTicketURL);
	}

	function notatestgetProjectRootUrl() {
		$createTicketURL = $this->getProjectRootUrl() . 'tp2/saisieTicket.html';
		$expectedCreateTicketURL = 'http://localhost:80/tpdsi/tp2/saisieTicket.html';
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
	
}


?>
