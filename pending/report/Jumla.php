<?php
class Jumla {

	//var $namba;
	var $jumla;

	public function Jumla() {
		//$this->jumla = 0;
		//$namba = array();
	}

	public function addElement($data) {
		if (is_numeric($data)) {
			$this->jumla = $this->jumla+$data;			
		} 					
	}

	public function getTotal() {
		if ($this->jumla ) {
			return $this->jumla;
			
		} elseif ($this->jumla===0 ) {
			return 0;		
		} else {
			return "-";	
		}
	}

}

?>