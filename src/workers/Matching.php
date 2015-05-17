<?php

	/**
	 * 
	 * Worker class for jobs regarding similarity detection.
	 * @author Ondrej Krpec, xkrpec01@stud.fit.vutbr.cz
	 *
	 */
	class Matching {
		
		############################  VARIABLES AND CONSTANT  ###########################
		
		private $resultProgramLength;
		private $resultVolume;
		private $resultDifficulty;
		
		private $resultDistance;
		private $similarBlocks;
		
		#################################  CONSTRUCTORS  ################################
		
		public function __construct() {
			$this->resultProgramLength = 0;
			$this->resultVolume = 0;
			$this->resultDifficulty = 0;
			$this->resultDistance = 0;
			$this->similarBlocks = 0;
		}
		
		####################################  METHODS  ##################################
		
		/**
		 * 
		 * Evaluates Halstead metrics. Each attribute represents how much similar functions among all functions have two correlated programs.
		 * Results are saved into this entity.
		 * @param $original First assignment from pair to comparison.
		 * @param $copied Second assignment from pair to comparison.
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
					
					if ($programLength == Constant::HUNDRED_PERCENT || $volume == Constant::HUNDRED_PERCENT 
							|| $difficulty == Constant::HUNDRED_PERCENT)
						break;
					
					if ($originalBlock->getProgramLength() > 0 && $copiedBlock->getProgramLength() > 0) {
						if ($originalBlock->getProgramLength() > $copiedBlock->getProgramLength()) {
							$tmpLength = $copiedBlock->getProgramLength() / $originalBlock->getProgramLength();
						} else {
							$tmpLength = $originalBlock->getProgramLength() / $copiedBlock->getProgramLength();
						}
						$tmpLength = $tmpLength * Constant::HUNDRED_PERCENT;
						
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
						$tmpVolume = $tmpVolume * Constant::HUNDRED_PERCENT;
						
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
						$tmpDifficulty = $tmpDifficulty * Constant::HUNDRED_PERCENT;
						
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
		
		/**
		 * 
		 * Evaluates the similarity of the two source codes using Levenshtein algorithm. 
		 * Results are saved into this entity.
		 * @param $original First assignment from the pair to comparison.
		 * @param $copied Second assignment from the pair to comparison.
		 */
		public function evaluateLevenshtein($original, $copied) {
			$this->similarBlocks = 0;
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
				if ($distance <= Constant::LEVENSHTEIN_THRESHOLD) {
					$this->similarBlocks++;
				}

				if (!is_null($distance))
					$this->resultDistance = ($this->resultDistance + self::recalculateDistance($distance)) / 2;
			} // end outer foreach
		}
		
		/**
		 * 
		 * Private method for recalculating Levenshtein distance into percentage similarity between the examined pair.
		 * @param $distance Levenshtein distance in range 0 - 255 that will be converted into percentage similarity.
		 * @return Percentage similarity of the input Levenshtein distance.
		 */
		private function recalculateDistance($distance) {
			return Constant::HUNDRED_PERCENT - (($distance * Constant::HUNDRED_PERCENT) / Constant::MAX_LEVENSHTEIN);
		}
		
		##############################  GETTERS AND SETTERS  ############################
		
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
		
		public function getSimilarBlocks() {
			return $this->similarBlocks;
		}
		
		public function setSimilarBlocks($similarBlocks) {
			$this->similarBlocks = $similarBlocks;
		}
		
	}

?>