<?php

	include_once __DIR__ . '/../metrics/levenshtein/Levenshtein.php';

	/**
	 * 
	 * Entity that contains processed data from source code.
	 * @author Ondrej Krpec, xkrpec01@stud.fit.vutbr.cz
	 *
	 */
	class TokenBlock {
		
		############################  VARIABLES AND CONSTANT  ###########################
		
		const TOKEN_TYPE = 0;
		const TOKEN_VALUE = 1;
		const TOKEN_LINE_NUMBER = 2;	
		
		private $tokens;
		
		private $halsteadBlocks;
		private $levenshteinBlocks;
		
		#################################  CONSTRUCTORS  ################################
		
		/**
		 * 
		 * Construct for creating entity with processed source code. As input serves stream
		 * of tokens that is parsed into this entity.
		 * @param $tokens Stream of tokens from input source code.
		 */
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
		
		####################################  METHODS  ##################################
		
		/**
		 * 
		 * Converts this entity into JSON array.
		 * @return array JSON array version of this entity.
		 */
		public function toJSON() {
			return array(
					'tokens' => $this->tokens,
					'halsteadBlocks' => $this->halsteadBlocks,
					'levenshteinBlocks' => $this->levenshteinBlocks,
			);
		}
		
		##############################  GETTERS AND SETTERS  ############################
		
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