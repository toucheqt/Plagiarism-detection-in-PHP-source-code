<?php

	class Matching {
		
		const AVG_RANGE = 1; // 100%
		const SETUP_RANGE = 0.1; // 20% TODO this could be setup by parameter
		const HUNDRED_PERCENT = 100;
		const MAX_LEVENSHTEIN = 255;
		
		private $resultProgramLength;
		private $resultVolume;
		private $resultDifficulty;
		
		private $resultDistance;
		
		public function __construct() {
			$this->resultProgramLength = 0;
			$this->resultVolume = 0;
			$this->resultDifficulty = 0;
		}
		
		/**
		 * 
		 * Evaluates Halstead metrics. Each attribute represents how much similar functions among all functions have two correlated programs.
		 * @param unknown_type $original
		 * @param unknown_type $copied
		 */
		public function evaluateHalstead($original, $copied) {
			
			foreach ($original as $originalBlock) {
				
				$programLength = 0;
				$volume = 0;
				$difficulty = 0;
				
				foreach ($copied as $copiedBlock) {
					
					$tmpLength = 0;
					$tmpVolume = 0;
					$tmpDifficulty = 0;
					
					if ($programLength == self::HUNDRED_PERCENT || $volume == self::HUNDRED_PERCENT || $difficulty == self::HUNDRED_PERCENT)
						break;
					
					if ($originalBlock->getProgramLength() > 0 && $copiedBlock->getProgramLength() > 0) {
						if ($originalBlock->getProgramLength() > $copiedBlock->getProgramLength()) {
							$tmpLength = $copiedBlock->getProgramLength() / $originalBlock->getProgramLength();
						} else {
							$tmpLength = $originalBlock->getProgramLength() / $copiedBlock->getProgramLength();
						}
						$tmpLength = $tmpLength * self::HUNDRED_PERCENT;
						
						if ($programLength == 0)
							$programLength = $tmpLength;
						else if ($programLength < $tmpLength)
							$programLength = $tmpLength;								
					}
					
					if ($originalBlock->getVolume() > 0 && $copiedBlock->getVolume() > 0) {
						if ($originalBlock->getVolume() > $copiedBlock->getVolume()) {
							$tmpVolume = $copiedBlock->getVolume() / $originalBlock->getVolume();
						} else {
							$tmpVolume = $originalBlock->getVolume() / $copiedBlock->getVolume();
						}
						$tmpVolume = $tmpVolume * self::HUNDRED_PERCENT;
						
						if ($volume == 0)
							$volume = $tmpVolume;
						else if ($volume < $tmpVolume)
							$volume = $tmpVolume;
					}
					
					if ($originalBlock->getDifficulty() > 0 && $copiedBlock->getVolume() > 0) {
						if ($originalBlock->getDifficulty() > $copiedBlock->getDifficulty()) {
							$tmpDifficulty = $copiedBlock->getDifficulty() / $originalBlock->getDifficulty();
						} else {
							$tmpDifficulty = $originalBlock->getDifficulty() / $copiedBlock->getDifficulty();
						}
						$tmpDifficulty = $tmpDifficulty * self::HUNDRED_PERCENT;
						
						if ($difficulty == 0)
							$difficulty = $tmpDifficulty;
						else if ($difficulty < $tmpDifficulty)
							$difficulty = $tmpDifficulty;
					}
					
				} // end inner foreach
				
				if ($programLength != 0) {
					if ($this->resultProgramLength == 0)
						$this->resultProgramLength = $programLength;
					else 
						$this->resultProgramLength = ($this->resultProgramLength + $programLength)/2;
				}
				
				if ($volume != 0) {
					if ($this->resultVolume == 0)
						$this->resultVolume = $volume;
					else 	
						$this->resultVolume = ($this->resultVolume + $volume)/2;
				}
				
				if ($difficulty != 0) {
					if ($this->resultDifficulty == 0)
						$this->resultDifficulty = $difficulty;
					else
						$this->resultDifficulty = ($this->resultDifficulty + $difficulty)/2;
				}
				
			} // end outer foreach
		}
		
		public function evaluateLevenshtein($original, $copied, $a, $b) {
			foreach ($original as $originalBlock) {
				
				$distance = null;
				foreach ($copied as $copiedBlock) {
					
					$tmpDistance = null;
					
					if (!is_null($distance) && $distance == 0)
						break;
						
					$tmpDistance = levenshtein($originalBlock, $copiedBlock);
					if (is_null($distance))
						$distance = $tmpDistance;
					else if ($distance > $tmpDistance)
						$distance = $tmpDistance;
				}
				if ($distance <= 30) {
					echo $a . " " . $b . "\n";
				}

				if (!is_null($distance))
					$this->resultDistance = ($this->resultDistance + self::recalculateDistance($distance)) / 2;
				
			}
		}
		
		private function recalculateDistance($distance) {
			return self::HUNDRED_PERCENT - (($distance * self::HUNDRED_PERCENT) / self::MAX_LEVENSHTEIN);
		}
		
		public function getResultProgramLength() {
			return $this->resultProgramLength;
		}
		
		public function setResultProgramLength($resultProgramLength) {
			$this->resultProgramLength = $resultProgramLength;
		}
		
		public function getResultVolume() {
			return $this->resultVolume;
		}
		
		public function setResultVolume($resultVolume) {
			$this->resultVolume = $resultVolume;
		}
		
		public function getResultDifficulty() {
			return $this->resultDifficulty;
		}
		
		public function setResultDifficulty($resultDifficulty) {
			$this->resultDifficulty = $resultDifficulty;
		}
		
		public function getResultDistance() {
			return $this->resultDistance;
		}
		
		public function setResultDistance($resultDistance) {
			$this->resultDistance = $resultDistance;
		}
		
	}

?>