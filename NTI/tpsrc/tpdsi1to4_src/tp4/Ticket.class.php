<?php
class Ticket {
	private $ticketID;
	private $applicationName;
	private $login;
	private $priority;
	private $type;
	private $creationDate;
	private $oneLiner;
	private $detailedDescription;
	private $attachmentName;

	function __construct($applicationName, $login, $priority, $type, $creationDate, $oneLiner, $detailedDescription, $attachmentName = '', $ticketID = -1) {
		$this->ticketID = $ticketID;
		$this->applicationName = $applicationName;
		$this->login = $login;
		$this->priority = $priority;
		$this->type = $type;
		$this->creationDate = $creationDate;
		$this->oneLiner = $oneLiner;
		$this->detailedDescription = $detailedDescription;
		$this->attachmentName = $attachmentName;
	}

	function getTicketID() {
		return $this->ticketID;
	}
	function setTicketID($ticketID) {
		$this->ticketID = $ticketID;
	}
	function getApplicationName() {
		return $this->applicationName;
	}
	function getLogin() {
		return $this->login;
	}
	function getPriority() {
		return $this->priority;
	}
	static function priorityCodeToText($priorityCode) {
		$priorityTextMap = array(
		5 => 'Très urgente',
		4 => 'Urgente',
		3 => 'Moyenne',
		2 => 'Faible',
		1 => 'Très faible'
		);
		$priorityText = $priorityTextMap[$priorityCode];
		return $priorityText;
	}
	function getPriorityText() {
		$priorityText = $this->priorityCodeToText($this->priority);
		return $priorityText;
	}
	function getType() {
		return $this->type;
	}
	function getCreationDate() {
		return $this->creationDate;
	}
	function getOneLiner() {
		return $this->oneLiner;
	}
	function getDetailedDescription() {
		return $this->detailedDescription;
	}
	function getAttachmentName() {
		return $this->attachmentName;
	}

	
}
?>