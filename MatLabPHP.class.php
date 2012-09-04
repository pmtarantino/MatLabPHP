<?php

/*
MatLabPHP
@author: Patricio Tarantino
@description: Using vectors and matrix syntaxis as MatLab to work in PHP.
@start-date: Sept 2012
*/

		

class MatLabPHP{

	// To Return Error Msgs in methods
	private function ErrorMsg($Msj){
		$ErrorMsg = array(
			'BadFormat'					=>	'Bad Format',
			'NotNum'					=>	'Value in vector is not Numeric',
			'NotSameColsRows'			=>	'The cols in each row should be the same',
			'ArgsNum'					=>	'Arguments must be numeric'
		);

		return $ErrorMsg[$Msj];
	}



	/*
	String to Vector:
	@desc: Transform a vector in the format of [1 2 3] to an array(1,2,3);
	@param: Number, Vector or Matrix. Ex: 1 or  [1 2 3] or [1 2 ; 3 4]
	@return: Array of Number, Vector or Matrix to operate in the class.
	*/
	public function StringToVector($Vector){
		if(is_array($Vector)){
			return $Vector;
		}

		elseif(is_numeric($Vector)){
			return array($Vector);
		}

		else{
			$Vector = trim($Vector);

			if(strpos($Vector,";")){ // If there are a few rows, then it is a matrix
				$Rows = explode(";",$Vector);
				foreach($Rows as $Key => $Row){
					if($Key == 0){
						$Row = substr($Row,1);
					}elseif($Key == count($Rows)-1){
						$Row = substr($Row,0,-1);
					}
					$ReturnVector[] = $this->StringToVector("[".$Row."]");
				}
				// Array of the Matrix finished. We should check if it is consistent.
				$Cols = count($ReturnVector[0]);
				foreach($ReturnVector as $Row){
					if(count($Row) != $Cols){
						return $this->ErrorMsg('NotSameColsRows');
						end();
					}
				}
				return $ReturnVector;
			}


			else if($Vector[0] != "[" || $Vector[strlen($Vector)-1] != "]"){ // Checking good format of [ numbers ]
				return $this->ErrorMsg('BadFormat');
				end();
			}

			else{
				$Vector = trim(substr($Vector,1,-1));
				$Values = explode(" ",$Vector);
					foreach($Values as $Value){
						if($Value != ""){
							if(is_numeric(trim($Value))){
								$VectorArray[] = trim($Value);
							}else{
								return $this->ErrorMsg('NotNum');
								end();
							}
						}
					}
				return $VectorArray;
			}
		}
	}

	/*
	Eye:
	@desc: Create the identity matrix;
	@param: cols and rows. 
	@return: Eye matrix
	*/
	public function eye($Cols,$Rows='eq'){
		$Rows = ($Rows == 'eq')? trim($Cols) : trim($Rows);
		$Cols = trim($Cols);
		
		if(!is_numeric($Cols) || !is_numeric($Rows)){
			return $this->ErrorMsg('ArgsNum');
			end();
		}

		$Matrix = array();
		for($c=1;$c<=$Cols;$c++){
				for($r=1;$r<=$Rows;$r++){
					$Matrix[$c][$r] = ($c == $r)? '1' : '0';
				}
		}
		return $Matrix;


	}

	/*
	Zeros:
	@desc: Create the a matrix of zeros;
	@param: cols and rows. 
	@return: Zero matrix
	*/
	public function zeros($Cols,$Rows='eq'){
		$Rows = ($Rows == 'eq')? trim($Cols) : trim($Rows);
		$Cols = trim($Cols);
		
		if(!is_numeric($Cols) || !is_numeric($Rows)){
			return $this->ErrorMsg('ArgsNum');
			end();
		}

		$Matrix = array();
		for($c=1;$c<=$Cols;$c++){
				for($r=1;$r<=$Rows;$r++){
					$Matrix[$c][$r] = '0';
				}
		}
		return $Matrix;


	}

	/*
	Length
	@desc: Gives back the max between cols and rows of a matrix
	@param: vector or matrix
	@return: int
	*/
	public function length($Vector,$Ret=0){
		$Vector = $this->StringToVector($Vector);
		if($Ret == 0){
			return max(count($Vector),count($Vector[1]));
		}else{
			$Rows = (isset($sumA[1])) ? count($sumA[1]) : 1;
			return array(count($Vector),$Rows);
		}
	}

	/*
	Sum
	@desc: Sumes two matrix or vectors or numbers
	@param: two vector or matrix or numbers
	@return: result
	*/
	public function sum($sumA,$sumB){
		$sumA = $this->StringToVector($sumA);
		$sumB = $this->StringToVector($sumB);
		$LengthA = $this->length($sumA,1);
		$LengthB = $this->length($sumB,1);

		if($LengthA[0] != $LengthB[0] || $LengthA[1] != $LengthB[1]){
			return $this->ErrorMsg('NotSameColsRows');
			end();
		}

		$Cols = count($sumA);
		$Rows = (isset($sumA[1])) ? count($sumA[1]) : 1;
		$Matrix = array();
		for($c=0;$c<$Cols;$c++){
				for($r=0;$r<$Rows;$r++){
					$Matrix[$c][$r] = ($sumA[$c][$r] + $sumB[$c][$r]);
				}
		}
		return $Matrix;

	}

}
?>

