<?php

	/**
	 * 
	 * Worker for processing tokens from the input source code.
	 * @author Ondrej Krpec, xkrpec01@stud.fit.vutbr.cz
	 *
	 */
	class TokensWorker {
		
		############################  VARIABLES AND CONSTANT  ###########################
		
		private $tokens;
		private $filename;
		
		#################################  CONSTRUCTORS  ################################
		
		public function __construct($filename) {
			$this->filename = $filename;
			$this->tokens = array();
			$this->loadTokens();
		}
		
		####################################  METHODS  ##################################
		
		/**
		 * 
		 * Method parses all functions from the input source code and saves them into an array.
		 * @param $tokens Tokens from the input source code
		 * @return Returns list of the functions that were found in the source code.
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
				
				if (!is_array($token) && $token == '{' && $isSaveTokens) {
					if (is_null($bracketCount))
						$bracketCount = 1;
					else 	
						$bracketCount++;
				} else if (!is_array($token) && $token == '}' && $isSaveTokens)
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
		 * Removes all comments from the input source code / tokens.
		 * @param $tokens Array of tokens representing the input source code.
		 */
		public function removeCommentsFromTokens() {
			if (is_null($this->tokens)) {
				Logger::error("Could not remove comments. There are no tokens.");
				return;
			}
			
			$tmpTokens = array();
			foreach ($this->tokens as $token) {
				if ($token[TokenBlock::TOKEN_TYPE] != T_COMMENT && $token[TokenBlock::TOKEN_TYPE] != T_DOC_COMMENT) {
					array_push($tmpTokens, $token);
				}
			}
			
			$this->tokens = $tmpTokens;
		}
		
		/**
		 * 
		 * Loads the input source code from the file. Removes whitespaces and convert
		 * source code into a stream of tokens.
		 * @throws InvalidArgumentException Throws an exception if filename was not specified.
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
			@$tmpArray = token_get_all($fileContent);
			
			// remove whitespaces
			foreach ($tmpArray as $token) {
				if ($token[0] != T_WHITESPACE) {
					array_push($this->tokens, $token);
				}
			}	
		}
		
		##############################  GETTERS AND SETTERS  ############################
		
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