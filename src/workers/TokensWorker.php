<?php

	/**
	 * 
	 * Enter description here ...
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class TokensWorker {
		
		private $tokens;
		private $filename;
		
		public function __construct($filename) {
			$this->filename = $filename;
			$this->tokens = array();
			$this->loadTokens();
		}
		
		/**
		 * 
		 * Returns array of tokens without comments.
		 */
		public function getTokensWithoutComments() {
			
			if (is_null($this->tokens)) {
				Logger::errorFatal("Input file is missing. Could not generate tokens.");
				throw new InvalidArgumentException();
			}
			
			$tmpArray = array();
			foreach ($this->tokens as $token) {
				if ($token[TokenBlock::TOKEN_TYPE] != T_COMMENT && $token[TokenBlock::TOKEN_TYPE] != T_DOC_COMMENT) {
					array_push($tmpArray, $token);
				}
			}
			
			return $tmpArray;
		}
		
		/**
		 * 
		 * Parse functions from tokens into array
		 * @param unknown_type $tokens
		 */
		public static function getFunctions($tokens) {
			$function = array();
			$functionList = array();
			$bracketCount = null;
			$isSaveTokens = false;
			
			foreach ($tokens as $token) {
				
				if (is_array($token) && $token[TokenBlock::TOKEN_TYPE] == T_FUNCTION) {
					$bracketCount = null;
					$isSaveTokens = true;
				}
				
				if ($isSaveTokens) {
					$function[] = $token;
				}
				
				if (!is_array($token) && $token == '{') {
					if (is_null($bracketCount))
						$bracketCount = 1;
					else 	
						$bracketCount++;
				}
				else if (!is_array($token) && $token == '}')
					$bracketCount--;
					
				// in case function ended
				if ($bracketCount === 0) {
					$isSaveTokens = false;
					$bracketCount = null;
					$functionList[] = $function;
					$function = array();
				}
			}
			
			return $functionList;
		}
		
		/**
		 * 
		 * Removes comments from array of tokens.
		 * @param array $tokens
		 * @throws InvalidArgumentException
		 */
		public static function removeCommentsFromTokens($tokens) {
			if (is_null($tokens)) {
				Logger::error("Could not remove comments. There are no tokens.");
				return;
			}
			
			$tmpTokens = array();
			foreach ($this->tokens as $token) {
				if ($token[TokenBlock::TOKEN_TYPE] != T_COMMENT && $token[TokenBlock::TOKEN_TYPE] != T_DOC_COMMENT) {
					array_push($tmpTokens, $token);
				}
			}
			
			return $tmpTokens;
		}
		
		/**
		 * 
		 * Gets tokens from input file.
		 * @throws InvalidArgumentException
		 */
		private function loadTokens() {
			
			if (is_null($this->filename)) {
				Logger::errorFatal('Can not read file. Filename is null.');
				throw new InvalidArgumentException();
			}

			if (!($fileContent = file_get_contents($this->filename))) {
				Logger::errorFatal('File ' . $this->filename . ' can not be opened.');
				throw new InvalidArgumentException();
			}
			$tmpArray = token_get_all($fileContent);
			
			// remove whitespaces
			foreach ($tmpArray as $token) {
				if ($token[0] != T_WHITESPACE) {
					array_push($this->tokens, $token);
				}
			}	
		}
		
		// ===== Getters/Setters =====
		
		public function getTokens() {
			return $this->tokens;
		}
		
		public function getFilename() {
			return $this->filename;
		}
		
		public function setFilename($filename) {
			$this->filename = $filename;
		}
		
	}	

?>