<?php

	include_once __DIR__ . '/Halstead.php';

	/**
	 * 
	 * Entity for representing inner structure of Halstead metrics.
	 * @author Ondrej Krpec, xkrpec01@stud.fit.vutbr.cz
	 *
	 */
	class HalsteadBlock {
		
		############################  VARIABLES AND CONSTANT  ###########################
		
		private $operators;
		private $operands;
		private $uniqueOperators;
		private $uniqueOperands;
		
		private $programLength = 0;
		private $volume;
		private $difficulty;
		
		#################################  CONSTRUCTORS  ################################
		
		public function __construct() {
			$this->uniqueOperators = array();
			$this->uniqueOperands = array();
			$this->operands = 0;
			$this->operators = 0;
			$this->programLength = 0;
			$this->volume = 0;
			$this->difficulty = 0;
		}
		
		####################################  METHODS  ##################################
		
		/**
		 * 
		 * Converts this entity into JSON array.
		 * @return array JSON array version of this entity.
		 */
		public function toJSON() {
			return array(
					'operators' => $this->operators,
					'operands' => $this->operands,
					'uniqueOperators' => $this->uniqueOperators,
					'uniqueOperands' => $this->uniqueOperands,
					'programLength' => $this->programLength,
					'volume' => $this->volume,
					'difficulty' => $this->difficulty,
			);
		}
		
		/**
		 * 
		 * Convert Halstead block in JSON format into this entity.
		 * @param $JSON Halstead block in JSON format
		 * @return $halsteadBlock Returns this entity with data from input JSON.
		 */
		public function fromJSON($JSON) {
			$JSON = (object) $JSON;
			$this->operators = $JSON->{Constant::PATTERN_OPERATORS};
			$this->operands = $JSON->{Constant::PATTERN_OPERANDS};
			$this->uniqueOperators = $JSON->{Constant::PATTERN_UNIQUE_OPERATORS};
			$this->uniqueOperands = $JSON->{Constant::PATTERN_UNIQUE_OPERANDS};
			$this->programLength = $JSON->{Constant::PATTERN_PROGRAM_LENGTH};
			$this->volume = $JSON->{Constant::PATTERN_VOLUME};
			$this->difficulty = $JSON->{Constant::PATTERN_DIFFICULTY};
			return $this;
		}
		
		/**
		 * 
		 * Adds operator to the unique operator class variable.
		 * @param $operator Operator that might be added into unique operators array.
		 * @return boolean Returns success of operation.
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
		 * 
		 * Adds operand to the unique operand class variable.
		 * @param $operand Operand that might be added into unique operands array.
		 * @return boolean Returns success of operation.
		 */
		public function addUniqueOperand($operand) {
			$this->operands++; // increment even if it might not be unique
			
			if ($this->isUniqueOperand($operand)) {
				$this->uniqueOperands[] = $operand;
				return true;
			}
			
			return false;
		}
		
		/**
		 * 
		 * Method to determine whether input operator is unique among the class array of unique operators.
		 * @param $operator Operator to consider as an unique operator.
		 * @return boolean Returns true if operator is unique among the class array of unique operators. Otherwise returns false.
		 */
		private function isUniqueOperator($operator) {
			foreach ($this->uniqueOperators as $usedOperator) {
				if ($operator[TokenBlock::TOKEN_TYPE] == $usedOperator[TokenBlock::TOKEN_TYPE]) {
					return false;
				}
			}
			return true;
		}
		
		/**
		 * 
		 * Method to determine whether input operand is unique among the class array of unique operand.
		 * @param $operand Operand to consider as an unique operand.
		 * @return boolean Returns true if operand is unique among the class array of unique operands. Otherwise returns false.
		 */
		private function isUniqueOperand($operand) {
			if (is_array($operand)) {
				foreach ($this->uniqueOperands as $usedOperand) {
					if ($operand[TokenBlock::TOKEN_TYPE] == $usedOperand[TokenBlock::TOKEN_TYPE] &&
							$operand[TokenBlock::TOKEN_VALUE] == $usedOperand[TokenBlock::TOKEN_VALUE]) {
						return false;			
					}
				}
			} else {
				foreach ($this->uniqueOperands as $usedOperand) {
					if ($usedOperand == $operand) {
						return false;
					}
				}
			}
			
			return true;
		}
		
		##############################  GETTERS AND SETTERS  ############################
		
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