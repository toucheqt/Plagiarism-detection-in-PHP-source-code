<?php

	include_once 'HalsteadBlock.php';

	/**
	 * 
	 * Enter description here ...
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class Halstead {
		
		const TOKEN_TYPE = 0;
		const TOKEN_VALUE = 1;
		const TOKEN_LINE_NUMBER = 2;
		
		const DEFAULT_BLOCK_LENGTH = 10;
		const MIN_BLOCK_LENGTH = 2;
		
		private $tokens;
		private $halsteadBlocks;
		
		public function __construct($tokens) {
			$this->tokens = $tokens;
			$this->halsteadBlocks = array();
		}
		
		// ====== Methods ======
		
	public function processTokens() {
			
			if (is_null($this->tokens)) {
				$errorMessage = "Input tokens can not be null.\n";
				echo $errorMessage;
				throw new InvalidArgumentException($errorMessage);
			}
			
			if (!is_null($blockLength) && $blockLength < self::MIN_BLOCK_LENGTH) {
				fprintf(STDERR, '[WARNING] Halstead.php >>> Code block length can not be smaller than ' 
						. self::MIN_BLOCK_LENGTH . ".\n");
				fprintf(STDERR, '[INFO] Halstead.php >>> Code block length was set to ' 
						. self::DEFAULT_BLOCK_LENGTH . ".\n");
				$blockLength = self::DEFAULT_BLOCK_LENGTH;
			}
			if (is_null($blockLength)) $blockLength = self::DEFAULT_BLOCK_LENGTH;
			
			$currentBlockLength = 0;
			$lastLine = 0;
			$tmpBlock = array();
			foreach ($this->tokens as $token) {
				if (is_array($token)) {
					if ($lastLine < $token[self::TOKEN_LINE_NUMBER]) {
						$lastLine = $token[self::TOKEN_LINE_NUMBER];
						$currentBlockLength++;
					}
				}
				
				if ($currentBlockLength < $blockLength) {
					array_push($tmpBlock, $token);
					continue;
				}
				$currentBlockLength = 0;
				$halsteadBlock = new HalsteadBlock($tmpBlock);
				$this->halsteadBlocks[] = $halsteadBlock;
				$tmpBlock = array();
				array_push($tmpBlock, $token);				
			}
			$halsteadBlock = new HalsteadBlock($tmpBlock);
			$this->halsteadBlocks[] = $halsteadBlock;	

			$this->evalMetrics();	
		}
		
		/**
		 * Evaluates halstead metrics for code block
		 */
		private function evalMetrics() {
			
			foreach ($this->halsteadBlocks as $block) {
				foreach ($block->getBlock() as $token) {
					if ($this->isOperator($token[self::TOKEN_TYPE])) {
						$block->addUniqueOperator($token);
					}
					else {
						$block->addUniqueOperand($token);
					}
				}

				$block->setProgramLength($this->evalProgramLength($block));
				$block->setVolume($this->evalVolume($block));
				$block->setDifficulty($this->evalDifficulty($block));
			}		
		}
		
		/**
		 * Evaluates program length of given codeblock
		 */
		private function evalProgramLength($block) {
			$N = count($block->getUniqueOperators()) * log(count($block->getUniqueOperators()), 2);
			$N += count($block->getUniqueOperands()) * log(count($block->getUniqueOperands()), 2);
			return $N;
		}
		
		/**
		 * Evaluates volume of given codeblock
		 */
		private function evalVolume($block) {
			$N = $block->getOperators() + $block->getOperands();
			$n = count($block->getUniqueOperators()) + count($block->getUniqueOperands());
			return $N * log($n, 2);
		}
		
		/**
		 * Evaluates difficulty of given codeblock
		 */
		private function evalDifficulty($block) {
			$D = (count($block->getUniqueOperators())/2);
			$D *= ($block->getOperands()/count($block->getUniqueOperands()));
			return $D;
		}
		
		private function isOperator($token) {
			switch ($token) {
				
				case 'T_CLONE':
					return true;
					
				case 'T_NEW':
					return true;
					
				case '[':
					return true;
					
				case 'T_POW': // **
					return true;
					
				case 'T_INC': // ++
					return true;
					
				case 'T_DEC': // --
					return true;
					
				case '~':
					return true;
					
				case 'T_INT_CAST': // (int) or (integer)
					return true;
					
				case 'T_DOUBLE_CAST': // (double) or (real) or (float)
					return true;
					
				case 'T_STRING_CAST': // (string)
					return true;
					
				case 'T_ARRAY_CAST': // (array)
					return true;
					
				case 'T_OBJECT_CAST': // (object)
					return true;
					
				case 'T_BOOL_CAST': // (bool) or (boolean)
					return true;
					
				case '@':
					return true;
					
				case 'T_INSTANCEOF':
					return true;
					
				case '!':
					return true;
					
				case '*':
					return true;
					
				case '/':
					return true;
					
				case '%':
					return true;
					
				case '+':
					return true;
					
				case '-':
					return true;
					
				case '.':
					return true;
					
				case 'T_SL': // <<
					return true;
					
				case 'T_SR': // >>
					return true;
					
				case '<':
					return true;
					
				case 'T_IS_SMALLER_OR_EQUAL': // <=
					return true;
					
				case '>':
					return true;
					
				case 'T_IS_GREATER_OR_EQUAL': // >=
					return true;
					
				case 'T_IS_EQUAL': // ==
					return true;
					
				case 'T_IS_NOT_EQUAL': // != or <>
					return true;
					
				case 'T_IS_IDENTICAL': // ===
					return true;
					
				case 'T_IS_NOT_IDENTICAL': // !==
					return true;
					
				case '&':
					return true;
					
				case '^':
					return true;
					
				case '|':
					return true;
				
				case 'T_BOOLEAN_AND':
					return true;
					
				case 'T_BOOLEAN_OR':
					return true;
					
				case '?':
					return true;
					
				case ':':
					return true;
					
				case '=';
					return true;
					
				case 'T_PLUS_EQUAL': // +=
					return true;
					
				case 'T_MINUS_EQUAL': // -=
					return true;
					
				case 'T_MUL_EQUAL': // *=
					return true;
					
				case 'T_POW_EQUAL': // **=
					return true;
					
				case 'T_DIV_EQUAL': // /=
					return true;
					
				case 'T_CONCAT_EQUAL': // .=
					return true;
					
				case 'T_MOD_EQUAL': // %=
					return true;
					
				case 'T_AND_EQUAL': // &=
					return true;
					
				case 'T_OR_EQUAL': // |=
					return true;
					
				case 'T_XOR_EQUAL': // ^=
					return true;
					
				case 'T_SL_EQUAL': // <<=
					return true;
					
				case 'T_SR_EQUAL': // >>=
					return true;
					
				case 'T_DOUBLE_ARROW': // =>
					return true;
					
				case 'T_LOGICAL_AND': // and
					return true;
					
				case 'T_LOGICAL_XOR': // xor
					return true;
					
				case 'T_LOGICAL_OR': // or
					return true;
					
				case ',':
					return true;
					
				default:
					return false;			
							
			} // end switch
		} // end isOperator method
		
		// ====== Getters/Setters ======
		
		public function getTokens() {
			return $this->tokens;
		}
		
		public function setTokens($tokens) {
			$this->tokens = $tokens;
		}
		
		public function getHalsteadBlocks() {
			return $this->halsteadBlocks;
		}
		
		public function setHalsteadBlock($halsteadBlocks) {
			$this->halsteadBlocks = $halsteadBlocks;
		}
		
	}

?>