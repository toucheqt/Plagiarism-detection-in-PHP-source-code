<?php

	class Halstead {
		
		// Amount of operands and operators used in selected block of code
		private $operatorsCount;
		private $operandsCount;
		
		// Unique operands and operators used in selected block of code
		private $uniqueOperators;
		private $uniqueOperands;
		
		public function __construct() {
		
			$this->uniqueOperators = array();
			$this->uniqueOperands = array();
		
		}
				
		
		/**
		 * Setter for class variable operatorsCount. In this variable should be stored count of all operators used in given source code.
		 * @param int Count of all operators used in given source code.
		 */
		private function setOperatorsCount($count) {
			$this->operatorsCount = $count;
		}
		
		/**
		 * Getter for class variable operatorsCount.
		 * @return int Returns count of all operators used in given source code.
		 */
		public function getOperatorsCount($count) {
			return $this->operatorsCount;
		}
		
		/**
		 * Setter for class variable operandsCount. In this variable should be stored count of all operands used in given source code.
		 * @param int Count of all operands used in given source code.
		 */
		private function setOperandsCount($count) {
			$this->operandsCount = $count;
		}
		
		/**
		 * Getter for class variable operandsCount.
		 * @return int Returns count of all operands used in given source code.
		 */
		public function getOperandsCount($count) {
			return $this->operandsCount;
		}
		
		/**
		 * Method will add given operator to the list of unique operators used in given block of code.
		 * @param Unique operator used in given block of code.
		 */
		public function addUniqueOperator($operator) {
			array_push($this->uniqueOperators, $operator);
		}
		
		/**
		 * Getter for class variable uniqueOperators.
		 * @return array Returns all unique operators used in given block of code.
		 */
		public function getUniqueOperators() {
			return $this->uniqueOperators;
		}
		
		/**
		 * Method will add given operands to the list of unique operands used in given block of code.
		 * @param Unique operand used in given block of code.
		 */
		private function setUniqueOperands($operand) {
			array_push($this->uniqueOperands, $operand);
		}
		
		/**
		 * Getter for class variable uniqueOperands.
		 * @return array Returns all unique operands used in given block of code.
		 */
		public function getUniqueOperands() {
			return $this->uniqueOperands;
		}
		
	}

?>
