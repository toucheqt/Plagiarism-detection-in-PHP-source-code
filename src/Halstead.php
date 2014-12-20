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
			
			$this->operandsCount = 0;
			$this->operatorsCount = 0;
		
		}
				
		
		/**
		 * Setter for class variable operatorsCount. In this variable should be stored count of all operators used in given source code.
		 * @param int Count of all operators used in given source code.
		 */
		public function setOperatorsCount($count) {
			$this->operatorsCount = $count;
		}
		
		/**
		 * Getter for class variable operatorsCount.
		 * @return int Returns count of all operators used in given source code.
		 */
		public function getOperatorsCount() {
			return $this->operatorsCount;
		}
		
		/**
		 * Setter for class variable operandsCount. In this variable should be stored count of all operands used in given source code.
		 * @param int Count of all operands used in given source code.
		 */
		public function setOperandsCount($count) {
			$this->operandsCount = $count;
		}
		
		/**
		 * Getter for class variable operandsCount.
		 * @return int Returns count of all operands used in given source code.
		 */
		public function getOperandsCount() {
			return $this->operandsCount;
		}
		
		/**
		 * Method will add given operator to the list of unique operators used in given block of code.
		 * Operator will not be added if its duplicate.
		 * @param Unique operator used in given block of code.
		 */
		public function addUniqueOperator($operator) {
			if (!in_array($operator, $this->getUniqueOperators())) {
				array_push($this->uniqueOperators, $operator);
			}
		}
		
		/**
		 * Getter for class variable uniqueOperators.
		 * @return array Returns all unique operators used in given block of code.
		 */
		public function getUniqueOperators() {
			return $this->uniqueOperators;
		}
		
		/**
		 * Method will add given operand to the list of unique operands used in given block of code.
		 * Operands wont be added if its duplicate.
		 * @param Unique operand used in given block of code.
		 */
		public function addUniqueOperand($operand) {
			if (!in_array($operand, $this->getUniqueOperands())) {
				array_push($this->uniqueOperands, $operand);
			}
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
