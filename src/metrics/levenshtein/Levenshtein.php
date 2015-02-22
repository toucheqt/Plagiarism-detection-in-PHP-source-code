<?php

	/**
	 * 
	 * Enter description here ...
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class Levenshtein {
		
		const MAX_STRING_SIZE = 255;
		
		/**
		 * Returns abstract block of tokens created from original tokens.
		 */
		public static function getAbstractBlocks($block) {
			$resourceArray = array();
			$singleResource = NULL;
			
			foreach ($block as $token) {
				
				$abstractToken = null;
				
				switch ($token[TokenBlock::TOKEN_TYPE]) {
					case T_COMMENT:
					case T_DOC_COMMENT:
						$abstractToken = 'T_COMMENT';
						break;
						
					case T_CONSTANT_ENCAPSED_STRING: // string "foo"
					case T_DNUMBER:
					case T_LNUMBER:
					case T_NUM_STRING:
					case T_VAR:
					case T_VARIABLE:
						$abstractToken = 'T_VAR';
						break;
						
					case T_ENCAPSED_AND_WHITESPACE: // promenna ve stringu "$foo "
					case T_GLOBAL:
						break;
						
					case T_PRIVATE:
					case T_PROTECTED:
					case T_PUBLIC:
						$abstractToken = 'T_PAMODIFIER';
						break;
						
					case T_FUNCTION:
						$abstractToken = 'T_FUNCTION';
						break;
						
					case T_INCLUDE:
					case T_INCLUDE_ONCE:
					case T_REQUIRE:
					case T_REQUIRE_ONCE:
						$abstractToken = 'T_INCLUDE';
						break;
						
					case T_STRING:
						$abstractToken = 'T_STRING';
						
					default:
						if (is_array($token)) {
							$abstractToken = token_name($token[TokenBlock::TOKEN_TYPE]);
						}
						else {
							$abstractToken = $token;
						}
						break;
				} // end switch
				
				if (!is_null($abstractToken)) {
					if (strlen($singleResource) + strlen($abstractToken) <= Levenshtein::MAX_STRING_SIZE) {
						$singleResource .= $abstractToken;
					}
					else {
						$resourceArray[] = $singleResource;
						$singleResource = $abstractToken;
					}
				}
			} // end foreach
			
			$resourceArray[] = $singleResource;
			return $resourceArray;
		}
	}
	
?>