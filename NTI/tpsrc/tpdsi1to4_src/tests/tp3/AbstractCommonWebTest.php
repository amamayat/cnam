<?php

if ( is_file(dirname(__FILE__) . '/../test.conf.php') )
  include(dirname(__FILE__) . '/../test.conf.php');
else {
  require_once ('simpletest/autorun.php');
  require_once('simpletest/web_tester.php');
}

class AbstractCommonWebTest extends WebTestCase {
	protected $urlRoot;
	protected $rootDirName;
	protected $testLogin;
	protected $testPassword;

	function setUp() {
		$this->testPassword = $this->testLogin = 'unittest';
		$this->computeProjectRootUrl();
	}
	
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
	
	function xxNotatestgetProjectRootUrl() {
		$this->assertEqual('http://localhost/tpdsi', $this->urlRoot);		
		$this->assertEqual('xx', dirname(__FILE__));
	}

	private function computeProjectRootUrl() {
		$fullPath = $this->getCurrentScriptFullPath();
		$targetDir = dirname($fullPath);
		// test dir is assumed to be at the root level
		$rootDir = dirname($targetDir);
		$rootDir = dirname($rootDir);
		$this->rootDirName = $rootDir;
		$urlRoot = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/' . $this->rootDirName;
		$this->urlRoot = $urlRoot . "/";
	}

	public function getProjectRootUrl() {
		return $this->urlRoot;
	}
	
	private function getCurrentScriptFullPath() {
		$requestURI = $_SERVER['REQUEST_URI'];
		$pos = strpos($requestURI, '?');
		if ($pos === false)
			return $requestURI;
		else
			return substr($requestURI, 0, $pos);
	}
	
	protected function submitLoginForm($login, $password) {
		$this->setField('login', $login);
		// test si le champ login existe bien
		$this->assertField('login', $login);
		$this->setField('password', $password);
		$this->assertField('password', $password);
		// test si le bouton submit existe bien
		$this->assertSubmitById('loginSubmit');
		// soumission du formulaire
		$this->clickSubmitById('loginSubmit');
		//$this->click('login');
	}

}

?>
