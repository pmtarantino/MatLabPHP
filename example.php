<?php
require_once('MatLabPHP.class.php');
require_once('linearAlg.class.php');

echo "<pre>";
$M = new Matrix("[3]");
$e = Matrix::eye(2);
$M->mult("[1]");
var_dump($M->get());
die();
var_dump($M->get(), $M->size());

$Z = Matrix::Zeros(2,2);
var_dump($Z->get(), $Z->size());

$E = Matrix::Eye(4);
var_dump($E->get(1,1), $E->size());

?>