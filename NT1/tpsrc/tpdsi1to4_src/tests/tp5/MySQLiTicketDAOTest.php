<?php

if ( is_file(dirname(__FILE__) . '/../test.conf.php') )
include(dirname(__FILE__) . '/../test.conf.php');
else {
  require_once ('simpletest/autorun.php');
}
include (dirname(__FILE__) . '/thistest.conf.php');
include_once "$targetDir/Ticket.class.php";
include_once "$targetDir/MySQLiTicketDAO.class.php";

class MySQLiTicketDAOTest extends UnitTestCase {
	private /* TicketDAO */ $_dao;

	function setUp() {
		$targetDir = $GLOBALS['targetDir'];
		$this->_dao = new MySQLiTicketDAO();
		$this->deleteAllTickets();
		// add some tickets
		$this->createInitialTickets();
	}

	function tearDown() {
		// $this->deleteAllTickets();
	}
	
	function testFindAll() {
		$methodExist = method_exists($this->_dao, 'findAll');
		if ( !$methodExist ) {
			$this->fail('La méthode findAll doit être définie');
			return;
		}
		$collection = $this->_dao->findAll();
		$this->assertEqual(2, count($collection), "");
		$ticket = $collection[0];
		$this->checkTicket1($ticket);
		$ticket = $collection[1];
		$this->checkTicket2($ticket);
	}
	
	function testSave() {
		$methodExist = method_exists($this->_dao, 'save');
		if ( !$methodExist ) {
			$this->fail('La méthode save doit être définie');
			return;
		}
		$ticket = new Ticket('applicationName3','login',3,'anomalie','05/02/2008','oneLiner3','detailedDescription3','attachmentName3');
		$this->_dao->save($ticket);
		$collection = $this->_dao->findAll();
		$ticket = $collection[count($collection)-1];
		$this->checkTicket($ticket, 'applicationName3','login',3,'anomalie','05/02/2008','oneLiner3','detailedDescription3');
	}
	
	private function checkTicket1($ticket) {
		$this->assertNotNull($ticket, 'ticket should not be null!');
		if ( $ticket == null ) return;
		$this->assertEqual('Paye', $ticket->getApplicationName(), "unexpected application name");
		$this->assertEqual(3, $ticket->getPriority(), "unexpected priority");
		$this->assertEqual('oneLiner 1', $ticket->getOneLiner());
		$this->assertEqual('detailed description 1', $ticket->getDetailedDescription());
	}
	
	function checkTicket2($ticket) {
		$this->assertNotNull($ticket, 'ticket should not be null!');
		if ( $ticket == null ) return;
		$this->assertEqual('Achats', $ticket->getApplicationName(), "unexpected application name");
		$this->assertEqual(5, $ticket->getPriority(), "unexpected priority");
		$this->assertEqual('oneLiner 2', $ticket->getOneLiner());
		$this->assertEqual('detailed description 2', $ticket->getDetailedDescription());
	}
	
	function checkTicket($ticket, $applicationName, $login, $priority, $type, $creationDate, $oneLiner, $detailedDescription) {
		$this->assertEqual($applicationName, $ticket->getApplicationName(), "unexpected application name");
		$this->assertEqual($priority, $ticket->getPriority(), "unexpected priority");
		$this->assertEqual($oneLiner, $ticket->getOneLiner());
		$this->assertEqual($detailedDescription, $ticket->getDetailedDescription());		
	}
	
	function createInitialTickets() {
		$ticket1 = new Ticket('Paye','unittest',3,'anomalie','06/02/2008','oneLiner 1','detailed description 1','attachmentName1');
		$ticket2 = new Ticket('Achats','unittest',5,'anomalie','06/02/2008','oneLiner 2','detailed description 2','attachmentName2');
		$this->_dao->save($ticket1);
		$this->_dao->save($ticket2);
	}

	function deleteAllTickets() {
		$this->_dao->deleteAll();
	}
	
	
}

?>
