<?php
require 'vendor/autoload.php';
require_once('linearAlg.class.php');
require 'vendor/simpletest/simpletest/autorun.php';

// Had to stop testing Exceptions because SimpleTest hasn't a clear way to handle them.

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

	function testBasicStringToVector(){
		$n = new Matrix(1);
		$this->assertTrue($n->get() == array(array(1)));
		$n = new Matrix('10');
		$this->assertTrue($n->get() == array(array(10)));
		$n = new Matrix('[1 2 3]');
		$this->assertTrue($n->get() == array(array(1,2,3)));
		$n = new Matrix('[1;2;3]');
		$this->assertTrue($n->get() == array(array(1),array(2),array(3)));
		$n = new Matrix('[1 2;3 4]');
		$this->assertTrue($n->get() == array(array(1, 2),array(3 ,4)));
		/*
		$this->expectException(new PatternExpectation("/should be the same/i"));
		$e = new Matrix('[1 2; 3 4 5]');
		$this->expectException(new PatternExpectation("/bad format/i"));
		$e = new Matrix('[1 2; 3 4');
		$this->expectException(new PatternExpectation("/bad format/i"));
		$e = new Matrix('1 2; 3 4]');
		$this->expectException(new PatternExpectation("/bad format/i"));
		$e = new Matrix('1 2; 3 4');
		$this->expectException(new PatternExpectation("/not numeric/i"));
		$e = new Matrix('[1 2; 3 a]');*/
	}

	function testGetAndSet(){
		$n = new Matrix('10');
		$this->assertTrue($n->get(1,1) == 10);
		$n = new Matrix('[1 2 3]');
		$this->assertTrue($n->get(1,2) == 2);

		$n = new Matrix('[1 2;3 4]');
		$this->assertTrue($n->get(2,1) == 3);
		$this->assertTrue($n->get(2) == array(3,4));
		$this->assertTrue($n->getRow(2) == array(3,4));
		$this->assertTrue($n->getCol(1) == array(1,3));
		$this->assertTrue($n->getCol(2) == array(2,4));
		$n->set(1,1,2);
		$this->assertTrue($n->get() == array(array(2,2),array(3,4)));
	}

	function testSize(){
		$n = new Matrix('10');
		$this->assertTrue($n->size() == array(1,1));
		$n = new Matrix('[1 2 3]');
		$this->assertTrue($n->size() == array(1,3));
		$n = new Matrix('[1 2;3 4]');
		$this->assertTrue($n->size() == array(2,2));
		$n = new Matrix('[1;3]');
		$this->assertTrue($n->size() == array(2,1));
	}

	function testSum(){
		$n = new Matrix('10');
		$n->sum(20);
		$this->assertTrue($n->get(1,1) == 30);

		$n = new Matrix('[1 2 3]');
		$n->sum('[2 2 2]');
		$this->assertTrue($n->get() == array(array(3,4,5)) );

		$n = new Matrix('[1 2; 3 4]');
		$n->sum('[100 100; 1000 1000]');
		$this->assertTrue($n->get() == array(array(101,102),array(1003,1004)));
		$n->sum('[100 100; 1000 1000]');
		$this->assertTrue($n->get() == array(array(201,202),array(2003,2004)));

		$m = Matrix::eye(2,2);
		$n->sum($m);
		$this->assertTrue($n->get() == array(array(202,202),array(2003,2005)));

	}

	function testScalarProd(){
		$n = new Matrix('[1 2 3]');
		$this->assertTrue($n->scalar_prod('[5 6 7]') == 38);
		$this->assertTrue($n->scalar_prod('[1 1 1]') == 6);
		$this->assertTrue($n->scalar_prod('[0 0 0]') == 0);
		$this->assertTrue($n->scalar_prod('[100 100 100]') == 600);
	}

	function testProd(){
		// For same size
		$n = new Matrix('[1 2; 3 4]');
		// $r = $n->prod(Matrix::eye(2,2));
		// $this->assertTrue($r->get() == $n->get());
		// $r = $n->prod('[1 2; 3 4]');
		// $this->assertTrue($r->get() == array(array(7,10),array(15,22)));
		// $r = $n->prod('[77 23; 0 12]');
		// $this->assertTrue($r->get() == array(array(77,47),array(231,117)));
		// $r = $n->prod(Matrix::zeros(2,2));
		// $this->assertTrue($r->get() == array(array(0,0),array(0,0)));
		// Dif size
		$r = $n->prod('[16;19]');
		$this->assertTrue($r->get() == array(array(54),array(124)));
	}
}
?>