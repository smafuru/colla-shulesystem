<?php
class Total {

	var $namba;
	var $jumla;

	public function Total() {
		$this->jumla = 0;
		$namba = array();
	}

	public function addElement($data) {
		//if (is_numeric($data)) {
			$this->namba[] = $data;
		//}

	}

	public function getTotal() {
		if (!empty($this->namba)) {
			foreach ($this->namba as $value) {
				$this->jumla = $this->jumla + $value;
			}
		} 

		if (!($this->jumla )) {
			return $this->jumla;
		} else {
			return "-";
		}

	}

	public function chapa() {
		print_r($this->namba);
	}
}

?>