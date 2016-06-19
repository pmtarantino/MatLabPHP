<?php
require_once('linearAlg.class.php');

echo "<pre>";
$M = new Matrix("[3 2; 1.11 2]");
$e = Matrix::eye(2,3);
var_dump($e->get());
$M->sum("[1 1; 1.00 1.00]");
var_dump($M->get());
die();
var_dump($M->get(), $M->size());

$Z = Matrix::Zeros(2,2);
var_dump($Z->get(), $Z->size());

$E = Matrix::Eye(4);
var_dump($E->get(1,1), $E->size());

?>