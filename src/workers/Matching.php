<?php

	define('PHP_INT_MIN', ~PHP_INT_MAX);

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
		
		private $resultHashesFirst;
		private $resultHashesSecond;
		private $similarityHashes;
		
		#################################  CONSTRUCTORS  ################################
		
		public function __construct() {
			$this->resultProgramLength = 0;
			$this->resultVolume = 0;
			$this->resultDifficulty = 0;
			$this->resultDistance = 0;
			$this->similarBlocks = 0;
			$this->resultHashesFirst = array();
			$this->resultHashesSecond = array();
			$this->similarityHashes = array();
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
		 * Compares two assignment using winnowing methods. Creates k-grams and hashes from them. Then uses private method
		 * that implements winnowing algorithm for selecting subsequence of hashes. Aftewards hashes are compared and result are returned
		 * in an array.
		 * @param $original First assignment for comparison by winnowing method.
		 * @param $copied Second assignment for comparison by winnowing method.
		 * @return Array with comparison results.
		 */
		public function evaluateWinnowing($original, $copied) {			
			$originalKGrams = self::createKGrams($original);
			$copiedKGrams = self::createKGrams($copied);
			
			self::winnowing($originalKGrams, 1);
			self::winnowing($copiedKGrams, 2);

			$lastOccurrenceLeft = null;
			$lastOccurrenceRight = null;
			foreach($this->resultHashesFirst as $kGram) {
				foreach($this->resultHashesSecond as $kGramSec) {
					if ($kGram[0] == $kGramSec[0]) {
						if (is_null($lastOccurrenceLeft || is_null($lastOccurrenceRight))) {
							$this->similarityHashes[] = '[' . $kGram[1] . '] - ' . $kGram[2] . ' [' . $kGramSec[1] . '] - ' . $kGramSec[2]; 
							$lastOccurrenceLeft = $kGram[1];
							$lastOccurrenceRight = $kGramSec[1];
						} else {
							$distanceLeft = 0;
							$distanceRight = 0;
							if ($lastOccurrenceLeft > $kGram[1])
								$distanceLeft = $lastOccurrenceLeft - $kGram[1];
							else 
								$distanceLeft = $kGram[1] - $lastOccurrenceLeft;
							if ($lastOccurrenceRight > $kGramSec[1])
								$distanceRight = $lastOccurrenceRight - $kGramSec[1];
							else 
								$distanceRight = $kGramSec[1] - $lastOccurrenceRight;
							
							if ($distanceLeft >= Constant::WINNOW_DISTANCE && $distanceRight >= Constant::WINNOW_DISTANCE) {
								$this->similarityHashes[] = '[' . $kGram[1] . '] - ' . $kGram[2] . ' [' . $kGramSec[1] . '] - ' . $kGramSec[2]; 
								$lastOccurrenceLeft = $kGram[1];
								$lastOccurrenceRight = $kGramSec[1];
							}
						}
					}
				}
			}

		}
		
		/**
		 * 
		 * Method creates an array of k-grams from input assignment.
		 * @param $assignment Assignment containing stream of tokens.
		 * @return Array of k-grams.
		 */
		private function createKGrams($assignment) {
			$assignment = (object) $assignment;
			$files = (object) $assignment->{Constant::PATTERN_FILES};
			$kGrams = array();
			
			foreach ($files as $file) {
				$file = (object) $file;
				$content = (object) $file->{Constant::PATTERN_CONTENT};
				$tokens = $content->{Constant::PATTERN_TOKENS};
				
				$tmpGram = "";
				$position = 0;
				foreach ($tokens as $token) {
					if (is_array($token)) {
						$tmpGram .= $token[0];
						$position = $token[2];
					} else {
						$tmpGram .= $token;
					}
					
					if (strlen($tmpGram) >= Constant::WINNOW_K_GRAM_SIZE) {
						$kGram = array();
						$kGram[] = hexdec(substr(md5($tmpGram), 0, 15));
						$kGram[] = $position;
						$kGram[] = $file->{Constant::PATTERN_FILENAME};
						$kGrams[] = $kGram;
						$tmpGram = "";
					}
				}
			}
			
			return $kGrams;
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
		
		/**
		 * 
		 * Method implementing algorithm winnowing.
		 * @param $kGrams Generated k-grams.
		 * @param $position Position of an assignment
		 */
		private function winnowing($kGrams, $position) {
			$window = array_fill(0, Constant::WINNOW_WINDOW_SIZE, null);
			for ($i = 0; $i < Constant::WINNOW_WINDOW_SIZE; ++$i)
				$window[$i] = PHP_INT_MIN;
			$windowRightEnd = 0;
			$minHashIndex = 0;
			foreach ($kGrams as $kGram) {
				$windowRightEnd = ($windowRightEnd + 1) % Constant::WINNOW_WINDOW_SIZE;
				$window[$windowRightEnd] = $kGram[0];
				if ($window[$windowRightEnd] == -1)
					break;
					
				if ($minHashIndex == $windowRightEnd) {
					for ($i = ($windowRightEnd - 1) % Constant::WINNOW_WINDOW_SIZE; 
							$i != $windowRightEnd; $i = ($i - 1 + Constant::WINNOW_WINDOW_SIZE) % Constant::WINNOW_WINDOW_SIZE) {
						if ($i > 0 && $window[$i] < $window[$minHashIndex])
							$minHashIndex = $i;
					}
					if ($position == 1) {
						$this->resultHashesFirst[] = SearchUtils::findKGramByHash($window[$minHashIndex], $kGrams); 
					} else if ($position == 2) {
						$this->resultHashesSecond[] = SearchUtils::findKGramByHash($window[$minHashIndex], $kGrams);
					}
				} else {
					if ($window[$windowRightEnd] <= $window[$minHashIndex]) {
						$minHashIndex = $windowRightEnd;
						if ($position == 1) {
							$this->resultHashesFirst[] = SearchUtils::findKGramByHash($window[$minHashIndex], $kGrams);
						} else if ($position == 2) {
							$this->resultHashesSecond[] = SearchUtils::findKGramByHash($window[$minHashIndex], $kGrams);
						}
					}
				}
			}
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
		
		public function getResultHashesFirst($resultHashesFirst) {
			return $this->resultHashesFirst;
		}
		
		public function setResultHashesFirst($resultHashesFirst) {
			$this->resultHashesFirst = $resultHashesFirst;
		}
		
		public function getResultHashesSecond($resultHashesSecond) {
			return $this->resultHashesSecond;
		}
		
		public function setResultHashesSecond($resultHashesSecond) {
			$this->resultHashesSecond = $resultHashesSecond;
		}
		
		public function getSimilarityHashes() {
			return $this->similarityHashes;
		}
		
		public function setSimilarityHashes($similarityHashes) {
			$this->similarityHashes = $similarityHashes;
		}
		
	}

?>