<?php

	include_once __DIR__ . '/HalsteadBlock.php';

	/**
	 * 
	 * Enter description here ...
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class Halstead {
		
		private function __construct() {}
		
		/**
		 * Evaluates halstead metrics for code block
		 * @param $halsteadBlock
		 * @param $block
		 */
		public static function evalMetrics($halsteadBlock, $block) {
			
			foreach ($block as $token) {
				if (Halstead::isOperator($token[TokenBlock::TOKEN_TYPE])) {
					$halsteadBlock->addUniqueOperator($token);
				}
				else {
					$halsteadBlock->addUniqueOperand($token);
				}
			}
			
			$halsteadBlock->setProgramLength(Halstead::evalProgramLength($halsteadBlock));
			$halsteadBlock->setVolume(Halstead::evalVolume($halsteadBlock));
			$halsteadBlock->setDifficulty(Halstead::evalDifficulty($halsteadBlock));
			
			return $halsteadBlock;
			
		}
		
		/**
		 * Evaluates program length of given codeblock
		 */
		private static function evalProgramLength($block) {
			$N = count($block->getUniqueOperators()) * log(count($block->getUniqueOperators()), 2);
			$N += count($block->getUniqueOperands()) * log(count($block->getUniqueOperands()), 2);
			return $N;
		}
		
		/**
		 * Evaluates volume of given codeblock
		 */
		private static function evalVolume($block) {
			$N = $block->getOperators() + $block->getOperands();
			$n = count($block->getUniqueOperators()) + count($block->getUniqueOperands());
			return $N * log($n, 2);
		}
		
		/**
		 * Evaluates difficulty of given codeblock
		 */
		private static function evalDifficulty($block) {
			$D = 0;
			
			if (count($block->getUniqueOperands()) != 0) {
				$D = (count($block->getUniqueOperators())/2);
				$D *= ($block->getOperands()/count($block->getUniqueOperands()));
			}
			
			return $D;
		}
		
		private static function isOperator($tokenType) {
			switch ($tokenType) {
				
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
		
	}		
?>