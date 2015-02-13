<?php

	include_once 'CodeBlock.php';

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
		private $codeBlocks;
		
		public function __construct($tokens) {
			$this->tokens = $tokens;
			$this->codeBlocks = array();
		}
		
		// ====== Methods ======
		
		public function processTokens($blockLength = NULL) {
			
			if (is_null($this->tokens)) {
				$errorMessage = "Input tokens can not be null.\n";
				echo $errorMessage;
				throw new InvalidArgumentException($errorMessage);
			}
			
			if ($blockLength < self::MIN_BLOCK_LENGTH) {
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
				$codeBlock = new CodeBlock($tmpBlock);
				$this->codeBlocks[] = $codeBlock;
				$tmpBlock = array();
				array_push($tmpBlock, $token);				
			}
			$codeBlock = new CodeBlock($tmpBlock);
			$this->codeBlocks[] = $codeBlock;	

			$this->evalMetrics();	
			
			foreach ($this->codeBlocks as $codeBlock) {
				echo "================ CODE BLOCK ===================\n";
				print_r($codeBlock->getBlock());
				echo "\n================= LENGTH =====================\n";
				echo $codeBlock->getProgramLength();
				echo "\n================= VOLUME =====================\n";
				echo $codeBlock->getVolume();
				echo "\n================= DIFFICULTY =====================\n";
				echo $codeBlock->getDifficulty();
			}
		}
		
		/**
		 * Evaluates halstead metrics for code block
		 */
		private function evalMetrics() {
			
			foreach ($this->codeBlocks as $codeBlock) {
				foreach ($codeBlock->getBlock() as $token) {
					if ($this->isOperator($token[self::TOKEN_TYPE])) {
						$codeBlock->addUniqueOperator($token);
					}
					else {
						$codeBlock->addUniqueOperand($token);
					}
				}

				$codeBlock->setProgramLength($this->evalProgramLength($codeBlock));
				$codeBlock->setVolume($this->evalVolume($codeBlock));
				$codeBlock->setDifficulty($this->evalDifficulty($codeBlock));
			}		
		}
		
		/**
		 * Evaluates program length of given codeblock
		 */
		private function evalProgramLength($codeBlock) {
			$N = count($codeBlock->getUniqueOperators()) * log(count($codeBlock->getUniqueOperators()), 2);
			$N += count($codeBlock->getUniqueOperands()) * log(count($codeBlock->getUniqueOperands()), 2);
			return $N;
		}
		
		/**
		 * Evaluates volume of given codeblock
		 */
		private function evalVolume($codeBlock) {
			$N = $codeBlock->getOperators() + $codeBlock->getOperands();
			$n = count($codeBlock->getUniqueOperators()) + count($codeBlock->getUniqueOperands());
			return $N * log($n, 2);
		}
		
		/**
		 * Evaluates difficulty of given codeblock
		 */
		private function evalDifficulty($codeBlock) {
			$D = (count($codeBlock->getUniqueOperators())/2);
			$D *= ($codeBlock->getOperands()/count($codeBlock->getUniqueOperands()));
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
		
	}

?>