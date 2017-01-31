<?php
include_once 'Ticket.class.php';
include_once 'TicketDAO.class.php';

class AppService {

	function __construct() {
	}

	static function printTicketAsTableRow($ticket, $tableRecordClass) {
		$applicationName = $ticket->getApplicationName();
		$priorityText = $ticket->getPriorityText();
		$type = $ticket->getType();
		$creationDate = $ticket->getCreationDate();
		$oneLiner = $ticket->getOneLiner();
		$detailedDescription = $ticket->getDetailedDescription();
		$str =<<<EOT
    <tr class="$tableRecordClass">
      <td class="center">$applicationName</td>
      <td class="center">$priorityText</td>
      <td class="center">$type</td>
      <td class="center">$creationDate</td>
      <td class="left">$oneLiner</td>
      <td class="left">$detailedDescription</td>
    </tr>
EOT;
		echo $str;
	}

	static function saveTicket($ticket) {
		$ticketDAO = new TicketDAO();
		$isOK = $ticketDAO->save($ticket);
		if ( !$isOK ) echo "***Debug*** sauvegarde interdite";
	}

	/**
	 * retourne la  collection (un tableau simple) de tous les tickets
	 * (le tableau retourné peut être vide.)
	 */
	static function getAllTickets() {
		$ticketDAO = new TicketDAO();
		return $ticketDAO->findAll();
	}

	/**
	 * retourne l'instance de Ticket d'ID $ticketId
	 */
	static function findTicketById($ticketId) {
		$ticketDAO = new TicketDAO();
		return $ticketDAO->findById($ticketId);
	}
}
?>
