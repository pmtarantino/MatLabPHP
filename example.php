<?
require_once('MatLabPHP.class.php');


$MatLab = new MatLabPHP();

$EYE = $MatLab->eye(10,12);
$ZEROS = $MatLab->zeros(3,2);
$MATR = $MatLab->StringToVector("[3 1;2 4]");

echo "<pre>";
var_dump($MatLab->sum('2','8'));
var_dump($MatLab->sum('[1 1; 0 0 ]','[8 9; 1 2]'));

?>