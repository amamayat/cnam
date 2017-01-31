<?php

if ( is_file(dirname(__FILE__) . '/../test.conf.php') )
  include(dirname(__FILE__) . '/../test.conf.php');
else {
  require_once ('simpletest/autorun.php');
  require_once('simpletest/web_tester.php');
}

class AbstractCommonWebTest extends WebTestCase {
	protected $urlRoot;
	protected $testPseudo;
	protected $testPassword;


	function startVerboseDiv() {
			echo "<div style='background-color:yellow; padding:2px; margin-left:10px;' >";
	}

	function endVerboseDiv() {
		echo "</div>";
	}


	function assertSubmitById($id) {
		$regexp="/(submit[^>]*id=.$id.)|(id=.$id.[^>]*submit)/";
		$result = parent::assertPattern($regexp,"Id not found");
		if ($result != '1') {
			$this->startVerboseDiv();
			echo "Impossible de trouver un formulaire dont le bouton de validation possède l'id <b>$id</b>";
			$this->EndVerboseDiv();	
		}
	}


	// rajoute des explications sur le non-fonctionnement du 
	// test si celui-ci n'est pas vérifié
	function assertPattern($pattern,$message = "") {
		$result = parent::assertPattern($pattern);
		if ($result != '1') {
			$this->startVerboseDiv();
			echo "Impossible de trouver le pattern <b>$pattern</b><br/>";
			if ($message != "") {
				echo "$message<br/>";
			}
			$this->endVerboseDiv();
		}
	}
	
	function assertLinkById($id, $expected = true, $message = '%s') {
		$result = parent::assertLinkById($id, $expected, $message);
		if ($result != '1') {
			$this->startVerboseDiv();
			echo "Un lien ayant pour id <b>$id</b> devrait exister";
			$this->endVerboseDiv();
		}
	}

	

	function setUp() {
		$this->testPassword = $this->testPseudo = 'unittest';
		$this->urlRoot = $this->getProjectRootUrl();
		// echo $this->urlRoot;
	}

	function xxNotatestgetProjectRootUrl() {
		$this->assertEqual('http://localhost/tpdsi', $this->urlRoot);		
		$this->assertEqual('xx', dirname(__FILE__));
	}

	function getProjectRootUrl() {
		$fullPath = $this->getCurrentScriptFullPath();
		$targetDir = dirname($fullPath);
		// test dir is assumed to be at the root level
		$rootDir = dirname($targetDir);
		$rootDir = dirname($rootDir);
		$urlRoot = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $rootDir;
		$urlRoot = $urlRoot . "/";
		return $urlRoot;
	}

	function getCurrentScriptFullPath() {
		$requestURI = $_SERVER['REQUEST_URI'];
		$pos = strpos($requestURI, '?');
		if ($pos === false)
			return $requestURI;
		else
			return substr($requestURI, 0, $pos);
	}
	
	function submitLoginForm($pseudo, $password) {
		$this->get($this->urlRoot);
		$this->setField('pseudo', $pseudo);
		// test si le champ pseudo existe bien
		$this->assertField('pseudo', $pseudo);
		$this->setField('motDePasse', $password);
		$this->assertField('motDePasse', $password);
		// test si le bouton submit existe bien
		$this->assertSubmitById('login');
		// soumission du formulaire
		$this->clickSubmitById('login');
	}

}

?>
