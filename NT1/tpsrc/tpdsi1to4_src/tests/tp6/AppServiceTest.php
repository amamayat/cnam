<?php

if ( is_file(dirname(__FILE__) . '/../test.conf.php') )
include(dirname(__FILE__) . '/../test.conf.php');
else {
  require_once ('simpletest/autorun.php');
}
include (dirname(__FILE__) . '/thistest.conf.php');
$targetDir = $GLOBALS['targetDir'];
include_once "$targetDir/AppService.class.php";

class AppServiceTest extends UnitTestCase {
	private $_service;

	function setUp() {
		$targetDir = $GLOBALS['targetDir'];
		$this->_filePath = dirname(__FILE__) . "/$targetDir/app.csv";
		$this->_service = new AppService();
		$this->createInitialApps($this->_filePath);
	}
	
	function testGetAppList() {
		$methodExist = method_exists($this->_service, 'getAppList');
		if ( !$methodExist ) {
			$this->fail('La méthode getAppList doit être définie');
			return;
		}
		$collection = $this->_service->getAppList();
		$this->assertEqual(3, count($collection));
		$product = $collection[0];
		$this->assertEqual('Paye', $product->getName());
		$product = $collection[1];
		$this->assertEqual('Inscriptions', $product->getName());
		$this->assertEqual('2', $product->getId());
	}
			
	function testGetProductMailFromName() {
		$methodExist = method_exists($this->_service, 'getProductMailFromName');
		if ( !$methodExist ) {
			$this->fail('La méthode getProductMailFromName doit être définie');
			return;
		}
		$mail = $this->_service->getProductMailFromName('Inscriptions');
		$this->assertEqual('inscriptions@cnam.fr', $mail);
	}
			
	function createInitialApps($file) {
		$f = fopen($file, 'w');
		$tickets = <<<EOT
1;  Paye;paye@cnam.fr
2;Inscriptions;inscriptions@cnam.fr
3;mon appli;moi@cnam.fr
EOT;
		fwrite($f, $tickets);
		fclose($f);
	}
}

?>
