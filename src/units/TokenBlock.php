<?php

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
		
		public function __construct($tokens) {
			$this->tokens = $tokens;
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