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
				$errorMessage = "Error while generating tokens without comments. Input file missing.\n";
				echo $errorMessage;
				throw new InvalidArgumentException($errorMessage);
			}
			
			$tmpArray = array();
			foreach ($this->tokens as $token) {
				if ($token[0] != T_COMMENT && $token[0] != T_DOC_COMMENT) {
					array_push($tmpArray, $token);
				}
			}
			
			return $tmpArray;
		}
		
		/**
		 * 
		 * Removes comments from array of tokens.
		 * @param array $tokens
		 * @throws InvalidArgumentException
		 */
		public static function removeCommentsFromTokens($tokens) {
			if (is_null($this->tokens)) {
				$errorMessage = "Tokens can not be null.\n";
				echo $errorMessage;
				throw new InvalidArgumentException($errorMessage);
			}
			
			$tmpTokens = array();
			foreach ($this->tokens as $token) {
				if ($token[0] != T_COMMENT && $token[0] != T_DOC_COMMENT) {
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
			
			// filename can not be null
			if (is_null($this->filename)) {
				$errorMessage = "Error while generating tokens from file. Filename can not be null.\n";
				echo $errorMessage;
				throw new InvalidArgumentException($errorMessage);
			}

			if (!($fileContent = file_get_contents($this->filename))) {
				$errorMessage = 'File ' . $this->filename . " can not be opened.\n";
				echo $errorMessage;
				throw new InvalidArgumentException($errorMessage);
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