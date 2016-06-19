<?php
require_once('simpletest/autorun.php');
require_once('linearAlg.class.php');

class TestBasicTypes extends UnitTestCase {
	function testZeros() {
		$z1 = Matrix::zeros(1);
		$this->assertTrue( $z1->get() == array(array(0)) );
		$z2 = Matrix::zeros(2);
		$this->assertTrue( $z2->get() == array(array(0, 0),array(0,0)) );
		$z12 = Matrix::zeros(1,2);
		$this->assertTrue( $z12->get() == array(array(0),array(0)) );
		$z31 = Matrix::zeros(3,1);
		$this->assertTrue( $z31->get() == array(array(0,0,0)) );
	}

	function testEyes() {
		$e1 = Matrix::eye(1);
		$this->assertTrue( $e1->get() == array(array(1)) );
		$e2 = Matrix::eye(2);
		$this->assertTrue( $e2->get() == array(array(1, 0),array(0,1)) );
		$e12 = Matrix::eye(1,2);
		$this->assertTrue( $e12->get() == array(array(1),array(0)) );
		$e31 = Matrix::eye(3,1);
		$this->assertTrue( $e31->get() == array(array(1,0,0)) );
		$e32 = Matrix::eye(3,2);
		$this->assertTrue( $e32->get() == array(array(1,0,0),array(0,1,0)) );
	}
}
?>