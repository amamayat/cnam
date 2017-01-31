<?php
if ( is_file(dirname(__FILE__) . '/../test.conf.php') )
  include(dirname(__FILE__) . '/../test.conf.php');
else {
  require_once ('simpletest/autorun.php');
}

class AllTests extends TestSuite {
    function __construct() {
        parent::__construct();
        $this->addFile('CreateTicketTests.php');
    }
}

?>