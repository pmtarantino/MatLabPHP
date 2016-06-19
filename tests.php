<?php
require_once('simpletest/autorun.php');
require_once('linearAlg.class.php');

class FirsTest extends UnitTestCase {
	function testZeros() {
		$z1 = Matrix::zeros(1);
		$this->assertTrue( $z1->get() == array(array(0)) );
		$z2 = Matrix::zeros(2);
		$this->assertTrue( $z2->get() == array(array(0, 0),array(0,0)) );
	}
}
?>