<?php

/*
MatLabPHP
@author: Patricio Tarantino
@description: Using vectors and matrix syntaxis as MatLab to work in PHP.
@start-date: Sept 2012
*/

		

class Matrix{

	protected $data;

	public function __construct($init){
		$this->data = self::StringToVector($init);
	}

	// Throw exceptions with a predefined message.
	private function ErrorMsg($Msj){
		$ErrorMsg = array(
			'BadFormat'					=>	'Bad Format',
			'NotNum'					=>	'Value in vector is not Numeric',
			'NotSameColsRows'			=>	'The cols in each row should be the same',
			'ArgsNum'					=>	'Arguments must be numeric'
		);

		throw new Exception($ErrorMsg[$Msj]);
	}


	/*
	String to Vector:
	@desc: Transform a vector in the format of [1 2 3] to an array(1,2,3);
	@param: Number, Vector or Matrix. Ex: 1 or  [1 2 3] or [1 2 ; 3 4]
	@return: Array of Number, Vector or Matrix to operate in the class.
	*/
	public static function StringToVector($Vector){
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
					$ReturnVector[] = self::StringToVector("[".$Row."]");
				}
				// Array of the Matrix finished. We should check if it is consistent.
				$Cols = count($ReturnVector[0]);
				foreach($ReturnVector as $Row){
					if(count($Row) != $Cols){
						throw new Exception ($this->ErrorMsg('NotSameColsRows'));
					}
				}
				return $ReturnVector;
			}


			else if($Vector[0] != "[" || $Vector[strlen($Vector)-1] != "]"){ // Checking good format of [ numbers ]
				throw new Exception ($this->ErrorMsg('BadFormat'));
			}

			else{
				$Vector = trim(substr($Vector,1,-1));
				$Values = explode(" ",$Vector);
				foreach($Values as $Value){
					if($Value != ""){
						if(is_numeric(trim($Value))){
							$VectorArray[] = floatval(trim($Value));
						}else{
							throw new Exception ($this->ErrorMsg('NotNum'));
						}
					}
				}
				return $VectorArray;
			}
		}
	}

	// Helper Constructors

	/*
	zeros:
	@desc: Create the a matrix of zeros;
	@param: cols and rows. 
	@return: Create zero matrix
	*/
	public static function zeros($Cols,$Rows='eq'){
		$Rows = ($Rows == 'eq')? trim($Cols) : trim($Rows);
		$Cols = trim($Cols);
		
		if(!is_numeric($Cols) || !is_numeric($Rows)){
			throw new Exception ($this->ErrorMsg('ArgsNum'));
		}

		$Zeros = array();
		for($r=0;$r<$Rows;$r++){
			for($c=0;$c<$Cols;$c++){
					$Zeros[$r][$c] = '0';
				}
		}

		return new self($Zeros);
	}


	/*
	Eye:
	@desc: Create the identity matrix;
	@param: cols and rows. 
	@return: Create eye matrix
	*/
	public static function eye($Cols,$Rows='eq'){
		$Rows = ($Rows == 'eq')? trim($Cols) : trim($Rows);
		$Cols = trim($Cols);
		
		if(!is_numeric($Cols) || !is_numeric($Rows)){
			throw new Exception ($this->ErrorMsg('ArgsNum'));
		}

		$Eye = array();
		for($r=0;$r<$Rows;$r++){
			for($c=0;$c<$Cols;$c++){
					$Eye[$r][$c] = ($c == $r)? '1' : '0';
				}
		}
		return new self($Eye);
	}

	/* Operations */

	/*
	get
	@desc: Get the elements from the matrix, or the matrix itself.
	@param: Col and/or row. If not passed, returns the 
	@return: Array of Numbers, or number if it's just an element.
	*/
	public function get($Col=false,$Row=false){
		if($Col){
			if($Row){
				return $this->data[$Col-1][$Row-1];
			} else{
				return $this->data[$Col-1];
			}
		} else {
			return $this->data;
		}
	}

	public function set($Col, $Row, $val){
		$this->data[$Col][$Row] = floatval($val);
	}

	/*
	size
	@desc: Return quantity of columns and rows
	@param: None
	@return: Array(Cols, Rows)
	*/
	public function size(){
		$Vector = $this->data;
		return array(count($Vector),count($Vector[0]));
	}

	/*
	sum
	@desc: Sumes two vectors
	@param: Vector to be added
	@return: None (modifies the current instance)
	*/
	public function sum($sum){
		if(!($sum instanceof Matrix)){
			$sum = new self($sum);
			$destroy = true;
		}

		$size = $this->size();
		$size_b = $sum->size();

		if( $size[0] != $size_b[0] || $size[1] != $size_b[1] ){
			$this->ErrorMsg('NotSameColsRows');
		}

		for($c=0;$c<$size[0];$c++){
			for($r=0;$r<$size[1];$r++){
				$this->set($c,$r, $this->data[$c][$r] + $sum->get($c+1,$r+1));
			}
		}

		if(isset($destroy)){
			unset($sum);
		}
	}

	/*
	mult
	@desc: Multiplies the current vector vs the one passed
	@param: Vector to be multiplied
	@return: Modifies the current instance
	*/
	public function mult($factor){
		if(!($factor instanceof Matrix)){
			$factor = new self($factor);
		}

		$size = $this->size();
		$size_b = $factor->size();

		if( $size[0] != $size_b[1] || $size[1] != $size_b[0] ){
			$this->ErrorMsg('NotSameColsRows');
		}

		for($c=0;$c<$size[0];$c++){
			for($r=0;$r<$size[1];$r++){
				$this->set($c,$r, $this->data[$c][$r] + $sum->get($c+1,$r+1));
			}
		}
	}

}
?>

