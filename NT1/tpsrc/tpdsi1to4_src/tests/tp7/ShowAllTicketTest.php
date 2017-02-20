<?php

if ( is_file(dirname(__FILE__) . '/../test.conf.php') )
  include(dirname(__FILE__) . '/../test.conf.php');
else {
  require_once ('simpletest/autorun.php');
	require_once('simpletest/web_tester.php');
}
include_once ('AbstractCommonWebTest.php');
include (dirname(__FILE__) . '/thistest.conf.php');
include_once "$targetDir/dao/MySQLiTicketDAO.class.php";
include_once "$targetDir/dao/MySQLiProductDAO.class.php";

class ShowAllTicketTest extends AbstractCommonWebTest {
	const SCRIPT_TO_TEST_NAME = '?content=view/showAllTickets.inc.php';
	private $tpFileRootDir;

	function setUp() {
		parent::setUp();
		$showTicketURL = $this->getProjectRootUrl() . TP_NAME . '/' . self::SCRIPT_TO_TEST_NAME;
		$relativeTpDir =  $this->rootDirName . '/' . TP_NAME;
		$this->tpFileRootDir = dirname(dirname(dirname(__FILE__))) . '/' . TP_NAME;
		$this->_ticketDao = new MySQLiTicketDAO();
		// delete persistent tickets 
		$this->deleteAllTickets();
		$this->createInitialTickets();
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
		$showTicketURL = $this->getProjectRootUrl() . TP_NAME . '/' . self::SCRIPT_TO_TEST_NAME;
		$expectedShowTicketURL = 'http://localhost:80/tpdsi/TP_NAME/showAllTickets.php';
		$this->assertEqual($expectedShowTicketURL, $showTicketURL);
	}
	
	function testUrgentTicketOnProduct1() {
		$application = 'Paye';
		$oneLiner = 'oneLiner 1';
		$priorityCode = 4; $priorityText = 'Urgente';
		// $type = 'anomalie';
		$detailedDescription = 'detailed description 1';

		$this->assertText($application);
		$this->assertText($priorityText);
		$this->assertText($oneLiner);
		$this->assertText($detailedDescription);
		// test color
		// $this->assertPattern('/<tr class="tab_bg_orange">/');
		$this->assertPattern('/<tr .*class=[" \']*tab_bg_orange/');
	}
	
	function testTicketOnProduct2() {
		$application = 'Messagerie';
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

	function createInitialTickets() {
		$productDAO = new MySQLiProductDAO();
		$product1 = $productDAO->findById(1);
		$ticket1 = new Ticket($product1,'unittest',4,'anomalie','02/01/2017','oneLiner 1','detailed description 1','attachmentName1');
		$product2 = $productDAO->findById(2);
		$ticket2 = new Ticket($product2,'unittest',3,'anomalie','02/01/2008','oneLiner 2','detailed description 2','attachmentName2');
		$this->_ticketDao->save($ticket1);
		$this->_ticketDao->save($ticket2);
	}

	function deleteAllTickets() {
		$this->_ticketDao->deleteAll();
	}
	
}

?>
