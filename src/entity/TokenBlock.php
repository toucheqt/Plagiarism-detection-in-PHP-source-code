<?php

	include_once '../metrics/levenshtein/Levenshtein.php';

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
		
		private $halsteadBlock;
		private $levenshteinBlocks;
		
		public function __construct($tokens) {
			$this->tokens = $tokens;
			$this->halsteadBlock = Halstead::evalMetrics(new HalsteadBlock(), $this->tokens);
			$this->levenshteinBlocks = Levenshtein::getAbstractBlocks($tokens);
		}
		
		// ===== Methods =======
		
		
		// ===== Getters/Setters =======
		
		public function getTokens() {
			return $this->tokens;
		}		
		
		public function setTokens($tokens) {
			$this->tokens = $tokens;
		}
	}

?>