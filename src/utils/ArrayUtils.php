<?php

	/**
	 * Util class containing methods for easy manipulation with arrays.
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class ArrayUtils {
		
		/**
		 * Generates sequence of unique pairs.
		 * @param assignments Generate all unique pairs of assignment-assignment
		 * @param templates Generate all unique pairs of assignment-template
		 */
		public static function getUniquePairs($assignments, $templates = null) {
			$source = (array) $assignments;
			$matchedPairs = array();
			
			foreach($source as $item) {
				$itemArray = (array) $item;
				unset($source[0]);
				$source = array_values($source);
				
				// generate unique assignment-assignment pairs
				foreach($source as $subItem) {
					$subItemArray = (array) $subItem;
					if (strcmp($subItemArray['dir'], $itemArray['dir'])) {
						$pair = array();
						$pair[] = $itemArray['dir'];
						$pair[] = $subItemArray['dir'];
						$matchedPairs[] = $pair;
					}
				}
				
				// generate unique assignment-template pairs
				if (!is_null($templates)) {
					foreach($templates as $subItem) {
						$subItemArray = (array) $subItem;
						if (strcmp($subItemArray['dir'], $itemArray['dir'])) {
							$pair = array();
							$pair[] = $itemArray['dir'];
							$pair[] = $subItemArray['dir'] . '-template';
							$matchedPairs[] = $pair;
						}
					}
				}
			}
			return $matchedPairs;
		}
		
	}

?>