<?php

	/**
	 * Entity container for encapsulation two token blocks objects - two assignments
	 * 
	 * @author xkrpecqt@gmail.com
	 *
	 */
	class Pair {
		
		const ORDER_FIRST = 1;
		const ORDER_SECOND = 2;
		
		private $firstAssignment;
		private $secondAssignment;
		
		private $firstHalstead = array();
		private $secondHalstead = array();
		
		public function Pair() {}
		
		private function parseHalstead($assignment, $order) {
			$files = $assignment->{'files'}; // TODO refaktorovat tohle na konstanty
			foreach ($files as $file) {
				$content = $file->{'content'};
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
		
		// ============= Getters/Setters =====================
		
		public function getFirstAssignment() {
			return $this->firstAssignment;
		}
		
		public function setFirstAssignment($assignment) {
			$this->firstAssignment = $assignment;
			self::parseHalstead($this->firstAssignment, self::ORDER_FIRST);
		}
		
		public function getSecondAssignment() {
			return $this->secondAssignment;
		}
		
		public function setSecondAssignment($assignment) {
			$this->secondAssignment = $assignment;
			self::parseHalstead($this->secondAssignment, self::ORDER_SECOND);
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
		
	}

?>