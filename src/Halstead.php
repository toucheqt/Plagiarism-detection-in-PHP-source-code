<?php

	/**
	 * Class used to get halstead metrics of given operators and operands. This can also compute these metrics such as
	 * calculated program length, volume and difficulty.
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 */

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
		 * Method returns calculated function length based on pattern N = n1 * log2(n1) + n2 * log2(n2) where
		 * n1 is the number of distinct operators and n2 is number of distinct operands.
		 * @return float Returns calculated function length.
		 */
		public function getProgramLength() {
			echo "operandy = " . count($this->uniqueOperands) . "\n";
			echo "operatory = " . count($this->uniqueOperators) . "\n";
			print_r($this->uniqueOperands);
			$N = count($this->getUniqueOperators()) * log(count($this->getUniqueOperators()), 2);
			$N += count($this->getUniqueOperands()) * log(count($this->getUniqueOperands()), 2);
			return $N;
		}
		
		/**
		 * Method returns volume of function based on pattern V = N * log2(n) where N = N1 + N2 and n = n1 + n2.
		 * N1 represents the total number of operators.
		 * N2 represents the total number of operands.
		 * n1 represents the distinct number of operators.
		 * n2 represents the distinct number of operands.
		 * @return float Returns volume of function.
		 */
		public function getVolume() {
			$N = $this->getOperatorsCount() + $this->getOperandsCount();
			$n = count($this->getUniqueOperators()) + count($this->getUniqueOperands());
			return $N * log($n, 2);
		}
		
		/**
		 * Method returns difficulty measure related to the difficulty of the program to write or understand based on pattern
		 * D = (n1/2) * (N2/n2) where
		 * n1 is the number of distinct operators.
		 * N2 represents the total number of operands.
		 * n2 represents the distinct number of operands.
		 * @return float Returns difficulty measure of given function.
		 */
		public function getDifficulty() {
			$D = (count($this->getUniqueOperators())/2) * ($this->getOperandsCount()/count($this->getUniqueOperands()));
			return $D;
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
