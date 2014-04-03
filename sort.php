<?php

$array = array( 'one' => array( 'two' => '3' , 'four' => '5,6,7,8' ) , 'eight' => array( 'nine' => array( 'ten' => '11' )   )   );
$json = '{
	"one": { "two": 3 , "four": [ 5,6,7]   },
	"eight": { "nine": { "ten":11 } }
}';

$sort = new mySort($json);
echo "<h2>sorted</h2>";
var_dump($sort->get_sorted());

echo "<h2>reversed</h2>";

var_dump($sort->reverse());



class mySort {

	private $sorted = [];
	private $reversed = [];
	private $path = [];
	private $json;
	
	public function __construct($json) {
		$this->json = $json;
		$this->init();
		
	}
	
	public function init() {
		$array = json_decode($this->json, true);
		return $this->sort($array , $this->sorted , $this->path);
	}


	private function sort($array, &$returnArray , &$path ) { 
		  
		foreach($array as $key=>$value)	{ 

			if(is_array($value)) {  
				
				 array_push($path , $key);
				 $this->sort($value , $returnArray , $path); 
			} else {
				//check if need to loop through values in container
				if ( strpos($value , ',') !== false ) {
					$values = explode("," , $value);
					
					for ($i = 0; $i < count($values); $i++) {
						
						$returnArray[implode($path , '/'). "/". $key ."/".$i] = $values[$i]; 
						
					}
				} else { 
					
					$returnArray[implode($path , '/'). "/" .$key] = $value;
					
				}
			} 
		}
		//reset path
		array_pop($path);
		return $returnArray;
	}
	
	public function reverse() {

		$reversed = [];
		//loop through each key as a path
		foreach ($this->sorted as $paths => $value) {
			
			$path = explode('/', $paths);
			
			while (null !== ($key = array_pop($path))) {
			
				$current = [$key => $value];
				
				$value = $current;
				
			}
			//merge the arrays together
			$reversed = array_replace_recursive($reversed, $value);
			
		}

		
		return $this->reversed = json_encode($reversed, JSON_PRETTY_PRINT);
	}


	public function get_sorted() {
		return $this->sorted;
	}
	
	public function get_reversed() {
		return $this->reversed;
	}
}

?>