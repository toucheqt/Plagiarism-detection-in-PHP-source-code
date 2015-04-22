<?php

	include_once __DIR__ . '/../metrics/levenshtein/Levenshtein.php';

	/**
	 * 
	 * Enter description here ...
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class TokenBlock {
		
		// ======= Constants =======
		const TOKEN_TYPE = 0;
		const TOKEN_VALUE = 1;
		const TOKEN_LINE_NUMBER = 2;	
		
		private $tokens;
		
		private $halsteadBlocks;
		private $levenshteinBlocks;
		
		public function __construct($tokens) {
			$this->tokens = $tokens;
			
			// get halstead for every function
			$functions = TokensWorker::getFunctions($tokens);
			$this->halsteadBlocks = array();
			foreach ($functions as $function) {
				$tmpBlock = Halstead::evalMetrics(new HalsteadBlock(), $function);
				$this->halsteadBlocks[] = $tmpBlock->toJSON();
			}
			$this->levenshteinBlocks = Levenshtein::getAbstractBlocks($tokens);
		}
		
		// ===== Methods =======
		
		public function toJSON() {
			return array(
					'tokens' => $this->tokens,
					'halsteadBlocks' => $this->halsteadBlocks,
					'levenshteinBlocks' => $this->levenshteinBlocks,
			);
		}
		
		// ===== Getters/Setters =======
		
		public function getTokens() {
			return $this->tokens;
		}		
		
		public function setTokens($tokens) {
			$this->tokens = $tokens;
		}
		
		public function getHalsteadBlocks() {
			return $this->halsteadBlocks;
		}
		
		public function setHalsteadBlocks($halsteadBlocks) {
			$this->halsteadBlocks = $halsteadBlocks;
		}
		
		public function getLevenshteinBlocks() {
			return $this->levenshteinBlocks;
		}
		
		public function setLevenshteinBlocks($levenshteinBlocks) {
			$this->levenshteinBlocks = $levenshteinBlocks;
		}
		
	}

?>