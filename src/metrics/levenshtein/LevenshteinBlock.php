<?php
	
	/**
	 * 
	 * Enter description here ...
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class LevenshteinBlock {
		
		private $resource;
		
		public function __construct() {
			$this->resource = array();
		}
		
		public function addResource($blockPart) {
			$this->resource[] = $blockPart;
		}
		
		// ====== Getters/Setters =====
		
		public function getResource() {
			return $this->resource;
		}
		
		public function setResource($res) {
			$this->resource = $res;
		}
		
	}

?>
