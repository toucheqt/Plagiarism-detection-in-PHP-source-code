<?php

	/**
	 * 
	 * Class for proccessing blocks of tokens into formatted blocks that can be compared by Levenshtein algorithm.
	 * @author Ondrej Krpec, xkrpec01@stud.fit.vutbr.cz
	 *
	 */
	class Levenshtein {
		
		####################################  METHODS  ##################################
		
		/**
		 *
		 * Creates abstract blocks of tokens from input tokens.
		 * @param $block Input tokens.
		 * @return Array of abstract blocks.
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
						
					// String e.g. "foo"
					case T_CONSTANT_ENCAPSED_STRING: 
						$abstractToken = 'T_VAR_STRING';
						break;
						
					case T_DNUMBER:
					case T_LNUMBER:
					case T_NUM_STRING:
					case T_VAR:
					case T_VARIABLE:
						$abstractToken = 'T_VAR';
						break;
						
					// variable in String e.g. "$foo"
					case T_ENCAPSED_AND_WHITESPACE:
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
					if (strlen($singleResource) + strlen($abstractToken) <= Constant::MAX_LEVENSHTEIN) {
						$singleResource .= $abstractToken;
					} else {
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