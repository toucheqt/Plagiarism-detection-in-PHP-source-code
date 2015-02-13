<?php

	/**
	 * 
	 * Enter description here ...
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class Levenshtein {
		
		private $resource;
		
		public function __construct($block) {
			$this->resource = array();
			$this->parseBlock($block);
		}
		
		private function parseBlock($block) {
			print_r($block);
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