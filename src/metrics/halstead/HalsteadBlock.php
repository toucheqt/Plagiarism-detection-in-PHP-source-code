<?php

	include_once 'Halstead.php';

	/**
	 * 
	 * Enter description here ...
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class HalsteadBlock {
		
		private $operators;
		private $operands;
		private $uniqueOperators;
		private $uniqueOperands;
		
		private $programLength;
		private $volume;
		private $difficulty;
		
		public function __construct() {
			$this->uniqueOperators = array();
			$this->uniqueOperands = array();
			$this->operands = 0;
			$this->operators = 0;
			$this->programLength = 0;
			$this->volume = 0;
			$this->difficulty = 0;
		}
		
		/**
		 * Adds operator to the unique operator class variable.
		 * Returns success of operation.
		 */
		public function addUniqueOperator($operator) {
			$this->operators++; // increment even if it might not be unique
			
			if ($this->isUniqueOperator($operator)) {
				$this->uniqueOperators[] = $operator;
				return true;
			}
			
			return false;
		}
		
		/**
		 * Adds operand to the unique operand class variable.
		 * Returns success of operation.
		 */
		public function addUniqueOperand($operand) {
			$this->operands++; // increment even if it might not be unique
			
			if ($this->isUniqueOperand($operand)) {
				$this->uniqueOperands[] = $operand;
				return true;
			}
			
			return false;
		}
		
		private function isUniqueOperator($operator) {
			foreach ($this->uniqueOperators as $usedOperator) {
				if ($operator[TokenBlock::TOKEN_TYPE] == $usedOperator[TokenBlock::TOKEN_TYPE]) {
					return false;
				}
			}
			return true;
		}
		
		private function isUniqueOperand($operand) {
			if (is_array($operand)) {
				foreach ($this->uniqueOperands as $usedOperand) {
					if ($operand[TokenBlock::TOKEN_TYPE] == $usedOperand[TokenBlock::TOKEN_TYPE] &&
							$operand[TokenBlock::TOKEN_VALUE] == $usedOperand[TokenBlock::TOKEN_VALUE]) {
						return false;			
					}
				}
			}
			else {
				foreach ($this->uniqueOperands as $usedOperand) {
					if ($usedOperand == $operand) {
						return false;
					}
				}
			}
			
			return true;
		}
		
		// ====== Getters/Setters ======
		public function getOperators() {
			return $this->operators;
		}
		
		public function setOperators($operators) {
			$this->operators = $operators;
		}
		
		public function getOperands() {
			return $this->operands;
		}
		
		public function setOperands($operands) {
			$this->operands = $operands;
		}
		
		public function getUniqueOperators() {
			return $this->uniqueOperators;
		}
		
		public function setUniqueOperators($operators) {
			$this->uniqueOperators = $operators;
		}
		
		public function getUniqueOperands() {
			return $this->uniqueOperands;
		}
		
		public function setUniqueOperands($operands) {
			$this->uniqueOperands = $operands;
		}
		
		public function getProgramLength() {
			return $this->programLength;
		}
		
		public function setProgramLength($length) {
			$this->programLength = $length;
		}
		
		public function getVolume() {
			return $this->volume;
		}
		
		public function setVolume($volume) {
			$this->volume = $volume;
		}
		
		public function getDifficulty() {
			return $this->difficulty;
		}
		
		public function setDifficulty($difficulty) {
			$this->difficulty = $difficulty;
		}
		
	}

?>