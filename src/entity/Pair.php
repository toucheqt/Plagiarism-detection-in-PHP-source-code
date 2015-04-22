<?php

	/**
	 * Entity container for encapsulation two token blocks objects - two assignments
	 * 
	 * @author xkrpecqt@gmail.com
	 *
	 */
	class Pair {
		
		private $firstAssignment;
		private $secondAssignment;
		
		public function Pair() {}
		
		public function getFirstAssignment() {
			return $this->firstAssignment;
		}
		
		public function setFirstAssignment($assignment) {
			$this->firstAssignment = $assignment;
		}
		
		public function getSecondAssignment() {
			return $this->secondAssignment;
		}
		
		public function setSecondAssignment($assignment) {
			$this->secondAssignment = $assignment;
		}
		
	}

?>