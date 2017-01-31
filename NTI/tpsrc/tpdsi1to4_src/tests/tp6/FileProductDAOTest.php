<?php

if ( is_file(dirname(__FILE__) . '/../test.conf.php') )
include(dirname(__FILE__) . '/../test.conf.php');
else {
  require_once ('simpletest/autorun.php');
}
include (dirname(__FILE__) . '/thistest.conf.php');
include_once "$targetDir/Product.class.php";
include_once "$targetDir/FileProductDAO.class.php";

class FileProductDAOTest extends UnitTestCase {
	private /* FileProductDAO */ $_dao;

	function setUp() {
		$this->_dao = new FileProductDAO('app.csv');
	}
	function tearDown() {
		$this->_dao = null;
	}
	function testFindById() {
		$product = $this->_dao->findById('3');
		$this->assertEqual(3, $product->getID());
		$this->assertEqual('moi@cnam.fr', $product->getMail());
	}
	function testFindAll() {
		$products = $this->_dao->findAll();
		$product = $products[2];
		$this->assertEqual(3, $product->getID());
		$this->assertEqual('moi@cnam.fr', $product->getMail());
	}
}

?>
