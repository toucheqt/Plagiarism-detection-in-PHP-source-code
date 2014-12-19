<?php

	class Halstead {
		
		private $usedOperators;
		private $usedOperands;
		
		private $uniqueOperators;
		private $uniqueOperands;
		
		
		/**
		 * Setter for class variable usedOperators. In this variable should be stored count of all operators used in given source code.
		 * @param int Count of all operators used in given source code.
		 */
		private function setUsedOperators($count) {
			$this->usedOperators = $count;
		}
		
		/**
		 * Getter for class variable usedOperators.
		 * @return int Returns count of all operators used in given source code.
		 */
		public function getUsedOperators($count) {
			return $this->usedOperators;
		}
		
		/**
		 * Setter for class variable usedOperands. In this variable should be stored count of all operands used in given source code.
		 * @param int Count of all operands used in given source code.
		 */
		private function setUsedOperands($count) {
			$this->usedOperands = $count;
		}
		
		/**
		 * Getter for class variable usedOperands.
		 * @return int Returns count of all operands used in given source code.
		 */
		public function getUsedOperands($count) {
			return $this->usedOperands;
		}
		
		/**
		 * Setter for class variable uniqueOperators. In this variable should be stored count of all unique operators used in given source code.
		 * @param int Count of all unique operators used in given source code.
		 */
		private function setUniqueOperators($count) {
			$this->uniqueOperators = $count;
		}
		
		/**
		 * Getter for class variable uniqueOperators.
		 * @return int Count of all unique operators used in given source code.
		 */
		public function getUniqueOperators() {
			return $this->uniqueOperators;
		}
		
		/**
		 * Setter for class variable uniqueOperands. In this variable should be stored count of all unique operands used in given source code.
		 * @param int Count of all unique operands used in given source code.
		 */
		private function setUniqueOperands($count) {
			$this->uniqueOperands = $count;
		}
		
		/**
		 * Getter for class variable uniqueOperands.
		 * @return int Count of all unique operands used in given source code.
		 */
		public function getUniqueOperands() {
			return $this->uniqueOperands;
		}
		
	}

?>
