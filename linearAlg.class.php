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
	private static function ErrorMsg($Msj){
		$ErrorMsg = array(
			'BadFormat' =>	'Bad Format',
			'NotNum' =>	'Value in vector is not Numeric',
			'NotSameColsRows' => 'The cols in each row should be the same',
			'ArgsNum' => 'Arguments must be numeric',
			'OutRange' => 'Out of range',				
		);

		throw new Exception($ErrorMsg[$Msj]);
	}


	/*
	String to Vector:
	@desc: Transform a vector in the format of [1 2 3] to an array(1,2,3);
	@param: Number, Vector or Matrix. Ex: 1 or  [1 2 3] or [1 2 ; 3 4]
	@return: Array of Number, Vector or Matrix to operate in the class.
	*/

	public static function StringToRow($Vector){
		$Vector = trim(substr($Vector,1,-1));
		$Values = explode(" ",$Vector);
		foreach($Values as $Value){
			if($Value != ""){
				if(is_numeric(trim($Value))){
					$VectorArray[] = floatval(trim($Value));
				}else{
					self::ErrorMsg('NotNum');
				}
			}
		}
		return $VectorArray;
	}


	public static function StringToVector($Vector){
		if(is_array($Vector)){
			if(is_array($Vector[0])){
				return $Vector;
			} else {
				return array($Vector);
			}
		}

		elseif(is_numeric($Vector)){
			return array(array($Vector));
		}

		else{
			$Vector = trim($Vector);

			if($Vector[0] != "[" || $Vector[strlen($Vector)-1] != "]"){ // Checking good format of [ numbers ]
				self::ErrorMsg('BadFormat');
			}
			else {
				$Rows = explode(";",$Vector);
				foreach($Rows as $Key => $Row){
					if($Key == 0){
						$Row = substr($Row,1);
					}elseif($Key == count($Rows)-1){
						$Row = substr($Row,0,-1);
					}
					$Row = str_replace(array('[',']'), '', $Row); // I dont like this.
					$ReturnVector[] = self::StringToRow("[".$Row."]");
				}
				// Array of the Matrix finished. We should check if it is consistent.
				$Cols = count($ReturnVector[0]);
				foreach($ReturnVector as $Row){
					if(count($Row) != $Cols){
						self::ErrorMsg('NotSameColsRows');
					}
				}
				return $ReturnVector;
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
			$this->ErrorMsg('ArgsNum');
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
			$this->ErrorMsg('ArgsNum');
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
	public function get($Row=false,$Col=false){
		if($Row){
			if($Col){
				return isset($this->data[$Row-1][$Col-1])? $this->data[$Row-1][$Col-1] : self::ErrorMsg('OutRange');
			} else{
				return $this->data[$Row-1];
			}
		} elseif($Col) {
			// If I am going to return only the column, I have to iterate over the rows.
			$data = array();
			$rows = $this->size();
			for($i=0;$i<$rows[0];$i++){
				$data[] = $this->data[$i][$Col-1];
			}
			return $data;
		} else {
			return $this->data;
		}
	}

	public function getRow($Row){
		return $this->get($Row);
	}

	public function getCol($Col){
		return $this->get(false, $Col);
	}

	public function set($Row, $Col, $val){
		if(isset($this->data[$Row-1][$Col-1])){
			$this->data[$Row-1][$Col-1] = floatval($val);
		} else {
			self::ErrorMsg('OutRange');
		}
	}

	/*
	size
	@desc: Return quantity of columns and rows
	@param: None
	@return: Array(Rows, Cols)
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

		for($r=1;$r<=$size[0];$r++){
			for($c=1;$c<=$size[1];$c++){
				$this->set($r,$c, $this->data[$r-1][$c-1] + $sum->get($r,$c));
			}
		}

		if(isset($destroy)){
			unset($sum);
		}
	}


	/*
	scalar_prod
	@desc: Calculates the scalar product (dot product, inner product) between this vector and the passed one.
	@param: Vector to be multiplied
	@return: Return an int
	*/

	public function scalar_prod($factor){
		if(!($factor instanceof Matrix)){
			$factor = new self($factor);
			$destroy = true;		
		}

		$size = $this->size();
		$size_b = $factor->size();

		if( $size[1] != $size_b[1] || $size[0] > 1 || $size_b[0] > 1){
			$this->ErrorMsg('NotSameColsRows');
		}

		$first_vector = $this->getRow(1);
		$second_vector = $factor->getRow(1);

		$result = 0;
		for($i=0;$i<$size[1];$i++){
			$result += $first_vector[$i] * $second_vector[$i];
		}

		if(isset($destroy)){
			unset($factor);
		}

		return $result;
	}

	/*
	prod
	@desc: Multiplies the current vector vs the one passed
	@param: Vector to be multiplied
	@return: Return a new instance of the results (diff sizes, we can not use the same)
	*/
	public function prod($factor){
		if(!($factor instanceof Matrix)){
			$factor = new self($factor);
			$destroy = true;		
		}

		$size = $this->size();
		$size_b = $factor->size();

		if( $size[1] != $size_b[0]){
			$this->ErrorMsg('NotSameColsRows');
		}

		$result = Matrix::eye($size_b[1],$size[0]);

		for($r=1;$r<=$size[0];$r++){
			for($c=1;$c<=$size_b[1];$c++){
				$row = $this->getRow($r);
				$col = $factor->getCol($c);

				$scalar_prod = 0;
				for($i=0;$i<count($row);$i++){
					$scalar_prod += $row[$i] * $col[$i];
				}

				$result->set($r,$c,$scalar_prod);
			}
		}

		if(isset($destroy)){
			unset($factor);
		}

		return $result;

	}

}