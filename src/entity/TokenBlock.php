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
		
		public $tokens;
		
		private $halsteadBlock;
		private $levenshteinBlocks;
		
		public function __construct($tokens) {
			$this->tokens = $tokens;
			
			// get halstead for every function
			$functions = TokensWorker::getFunctions($tokens);
			$this->halsteadBlock = array();
			foreach ($functions as $function) {
				$tmpBlock = Halstead::evalMetrics(new HalsteadBlock(), $function);
				$this->halsteadBlock[] = $tmpBlock->toJson();
			}
			$this->levenshteinBlocks = Levenshtein::getAbstractBlocks($tokens);
		}
		
		// ===== Methods =======
		
		public function toJson() {
			return array(
					'tokens' => $this->tokens,
					'halsteadBlocks' => $this->halsteadBlock,
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
	}

?>