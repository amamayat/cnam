<?php
include_once 'Ticket.class.php';

class TicketDAO {

	private $filePath; // chemin du fichier utilisé pour la persistance des tickets
	private $nextTicketID; // ID du prochain ticket créé

	function __construct($filePath = 'tickets.csv') {
		$this->filePath = $filePath;
		$lastTicketID = $this->getLastID();
		$this->nextTicketID = $lastTicketID + 1;
	}

	/**
	 * retourne la  collection (un tableau simple) de tous les tickets
	 * (le tableau retourné peut être vide.)
	 */
	function findAll() {
		$result = array ();
		$fd = fopen($this->filePath, 'r'); // ouverture du fichier en lecture
		if (!$fd)
			return $result;
		while ($line = fgets($fd)) {
			list ($ticketID, $applicationName, $login, $priority, $type, $creationDate, $oneLiner, $detailedDescription, $attachmentName) = explode(';', $line);
			$newTicket = new Ticket($applicationName, $login, $priority, $type, $creationDate, $oneLiner, $detailedDescription, $attachmentName, $ticketID);
			$result[] = $newTicket; // ajoute le nouveau ticket au tableau
		}
		fclose($fd); // fermeture du fichier
		return $result;
	}

	/**
	 * retourne l'instance de Ticket d'ID $searchedTicketId
	 * (ou null si le ticket n'est pas trouvé)
	 */
	function findById($searchedTicketId) {
		$result = null;
		$fd = fopen($this->filePath, 'r'); // ouverture du fichier en lecture
		if (!$fd)
			return $result;
		while ($line = fgets($fd)) {
			list ($ticketID, $applicationName, $login, $priority, $type, $creationDate, $oneLiner, $detailedDescription, $attachmentName) = explode(';', $line);
			if ($ticketID == $searchedTicketId) {
				$newTicket = new Ticket($applicationName, $login, $priority, $type, $creationDate, $oneLiner, $detailedDescription, $attachmentName, $ticketID);
				$result = $newTicket;
				break;
			}
		}
		fclose($fd); // fermeture du fichier
		return $result;
	}

	function save($ticket) {
		$this->initTicketID($ticket);
		$line = $ticket->getTicketID() . ";" .  $ticket->getApplicationName() . ";" .  $ticket->getLogin() . ";" .  $ticket->getPriority() . ";" .  $ticket->getType() . ";" .  $ticket->getCreationDate() . ";" .  $ticket->getOneLiner() . ";" .  $ticket->getDetailedDescription() . ";" .  $ticket->getAttachmentName();
		$fd = fopen($this->filePath, 'a+'); // ouverture du fichier en écriture 'append'
		if (!$fd)
			return false;
		$line .= "\n"; // concaténation d'un saut de ligne
		$isOK = fputs($fd, $line); // écriture de la ligne en fin de fichier
		fclose($fd); // fermeture du fichier
		return $isOK;
	}

	private function initTicketID($ticket) {
		if ($ticket->getTicketID() != -1)
			return;
		// sinon on initialise l'ID 
		$ticket->setTicketID($this->nextTicketID);
	}

	private function getLastID() {
		return $this->getNumberOfLines($this->filePath);
	}

	private function getNumberOfLines($file) {
		$fd = @fopen($this->filePath, 'r'); // ouverture du fichier en lecture
		if (!$fd)
			return -1;
		$result = 0;
		while ($line = fgets($fd)) {
			$result++;
		}
		fclose($fd); // fermeture du fichier
		return $result;
	}
}
?>
