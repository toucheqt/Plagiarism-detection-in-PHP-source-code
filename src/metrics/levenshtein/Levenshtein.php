<?php

	/**
	 * 
	 * Enter description here ...
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class Levenshtein {
		
		/**
		 * Returns abstract block of tokens created from original tokens.
		 */
		public static function getAbstractBlock($block) {
			$resourceArray = array();
			$singleResource = NULL;
			
			foreach ($block as $token) {
				switch ($token[TokenBlock::TOKEN_TYPE]) {
					case T_COMMENT:
					case T_DOC_COMMENT:
						$singleResource .= 'T_COMMENT';
						break;
						
					case T_CONSTANT_ENCAPSED_STRING: // string "foo"
					case T_DNUMBER:
					case T_LNUMBER:
					case T_NUM_STRING:
					case T_VAR:
					case T_VARIABLE:
						$singleResource .= 'T_VAR';
						break;
						
					case T_ENCAPSED_AND_WHITESPACE: // promenna ve stringu "$foo "
					case T_GLOBAL:
						break;
						
					case T_PRIVATE:
					case T_PROTECTED:
					case T_PUBLIC:
						$singleResource .= 'T_PAMODIFIER';
						break;
						
					case T_FUNCTION:
						$singleResource .= 'T_FUNCTION';
						break;
						
					case T_INCLUDE:
					case T_INCLUDE_ONCE:
					case T_REQUIRE:
					case T_REQUIRE_ONCE:
						$singleResouce .= 'T_INCLUDE';
						break;
						
					case T_STRING:
						$singleResouce .= 'T_STRING';
						
					default:
						if (is_array($token)) {
							$singleResource .= token_name($token[TokenBlock::TOKEN_TYPE]);
						}
						else {
							$singleResource .= $token;
						}
						break;
				}
			}
		}
	}
	
?>