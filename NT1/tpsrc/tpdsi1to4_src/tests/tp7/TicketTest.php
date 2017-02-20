<?php

if ( is_file(dirname(__FILE__) . '/../test.conf.php') )
include(dirname(__FILE__) . '/../test.conf.php');
else {
  require_once ('simpletest/autorun.php');
}
include (dirname(__FILE__) . '/thistest.conf.php');
include_once "$targetDir/domain/Ticket.class.php";
include_once "$targetDir/domain/Product.class.php";

class TicketTest extends UnitTestCase {
	private /* Ticket */ $_ticket;
	private /* Product */ $_product;
	
	function setUp() {
		$this->_product = new Product('applicationName3', 'x@x.fr');
		$this->_ticket = new Ticket($this->_product,'login',3,'anomalie','02/01/2017','oneLiner3','detailedDescription3','attachmentName3',33);		
	}

	function testAllGetters() {
		$this->assertEqual('applicationName3', $this->_ticket->getApplicationName());
		$this->assertEqual('login', $this->_ticket->getLogin());
		$this->assertEqual(3, $this->_ticket->getPriority());
		$this->assertEqual('anomalie', $this->_ticket->getType());
		$this->assertEqual('02/01/2017', $this->_ticket->getCreationDate());
		$this->assertEqual('oneLiner3', $this->_ticket->getOneLiner());
		$this->assertEqual('detailedDescription3', $this->_ticket->getDetailedDescription());
		$this->assertEqual('attachmentName3', $this->_ticket->getAttachmentName());
		$this->assertEqual(33, $this->_ticket->getId());
		$this->assertEqual('Moyenne', $this->_ticket->getPriorityText());
	}

	function testPriorityCodeToText() {
		$this->assertEqual('Très urgente', Ticket::priorityCodeToText(5));
		$this->assertEqual('Urgente', Ticket::priorityCodeToText(4));
		$this->assertEqual('Moyenne', Ticket::priorityCodeToText(3));
		$this->assertEqual('Faible', Ticket::priorityCodeToText(2));
		$this->assertEqual('Très faible', Ticket::priorityCodeToText(1));
	}
	
}

?>
