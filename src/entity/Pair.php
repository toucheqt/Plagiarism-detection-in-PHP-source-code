<?php

	/**
	 * 
	 * Entity container for encapsulation two token blocks objects - two assignments
	 * @author Ondrej Krpec, xkrpec01@stud.fit.vutbr.cz
	 *
	 */
	class Pair {
		
		############################  VARIABLES AND CONSTANT  ###########################
		
		const ORDER_FIRST = 1;
		const ORDER_SECOND = 2;
		
		private $firstAssignment;
		private $secondAssignment;
		
		private $firstHalstead = array();
		private $secondHalstead = array();
		
		private $firstLevenshtein = array();
		private $secondLevenshtein = array();
		
		#################################  CONSTRUCTORS  ################################
		
		public function Pair() {}
		
		####################################  METHODS  ##################################
		
		/**
		 * 
		 * Method parses Halstead data from the assignment and stores them into this entity.
		 * @param $assignment Entity with preprocessed assignment containing informations about Halstead metrics.
		 * @param $order Parameter to specify order of the assignment in the pair.
		 */
		private function parseHalstead($assignment, $order) {
			$assignment = (object) $assignment;
			$files = (object) $assignment->{Constant::PATTERN_FILES};
			foreach ($files as $file) {
				$file = (object) $file;
				$content = (object) $file->{Constant::PATTERN_CONTENT};
				$halsteadBlocks = $content->{'halsteadBlocks'};
				foreach ($halsteadBlocks as $block) {
					if ($order == self::ORDER_FIRST) {
						$tmpHalstead = new HalsteadBlock();
						$this->firstHalstead[] = $tmpHalstead->fromJSON($block);
					} else if ($order == self::ORDER_SECOND) {
						$tmpHalstead = new HalsteadBlock();
						$this->secondHalstead[] = $tmpHalstead->fromJSON($block);
					}
				}
			}
		}
		
		/**
		 * 
		 * Method parses Levenshtein data from the assignment and stores them into this entity.
		 * @param $assignment Entity with preprocessed assignment containing informations about Halstead metrics.
		 * @param $order Parameter to specify order of the assignment in the pair.
		 */
		private function parseLevenshtein($assignment, $order) {
			$assignment = (object) $assignment;
			$files = (object) $assignment->{Constant::PATTERN_FILES};
			foreach ($files as $file) {
				$file = (object) $file;
				$content = (object) $file->{Constant::PATTERN_CONTENT};
				$levenshteinBlocks = $content->{'levenshteinBlocks'};
				foreach ($levenshteinBlocks as $block) {
					if ($order == self::ORDER_FIRST)
						$this->firstLevenshtein[] = $block;
					else if ($order == self::ORDER_SECOND)
						$this->secondLevenshtein[] = $block;
				}
			}
		}
		
		##############################  GETTERS AND SETTERS  ############################
		
		public function getFirstAssignment() {
			return $this->firstAssignment;
		}
		
		public function setFirstAssignment($assignment) {
			$this->firstAssignment = $assignment;
			self::parseHalstead($this->firstAssignment, self::ORDER_FIRST);
			self::parseLevenshtein($this->firstAssignment, self::ORDER_FIRST);
		}
		
		public function getSecondAssignment() {
			return $this->secondAssignment;
		}
		
		public function setSecondAssignment($assignment) {
			$this->secondAssignment = $assignment;
			self::parseHalstead($this->secondAssignment, self::ORDER_SECOND);
			self::parseLevenshtein($this->secondAssignment, self::ORDER_SECOND);
		}
		
		public function getFirstHalstead() {
			return $this->firstHalstead;
		}
		
		public function setFirstHalstead($firstHalstead) {
			$this->firstHalstead = $firstHalstead;
		}
		
		public function getSecondHalstead() {
			return $this->secondHalstead;
		}
		
		public function setSecondHalstead($secondHalstead) {
			$this->secondHalstead = $secondHalstead;
		}
		
		public function getFirstLevenshtein() {
			return $this->firstLevenshtein;
		}
		
		public function setFirstLevenshtein($levenshtein) {
			$this->firstLevenshtein = $levenshtein;
		}
		
		public function getSecondLevenshtein() {
			return $this->secondLevenshtein;
		}
		
		public function setSecondLevenshtein($levenshtein) {
			$this->secondLevenshtein = $levenshtein;
		}
		
	}

?>