<?php

	/**
	 * 
	 * Utility class for searching items in an array.
	 * @author Ondrej Krpec, xkrpec01@stud.fit.vutbr.cz
	 *
	 */
	class SearchUtils {
		
		/**
		 * 
		 * Searches for the needle in the array. 
		 * @param $needle Needle to search for.
		 * @param $array Array in which the search will begin.
		 * @return boolean Returns true if the needle is in the array, false otherwise
		 */
		public static function inArray($needle, $array) {
			foreach ($array as $item) {
				if (!strcmp($needle, $item))
					return true;
			}
			return false;
		}
		
		/**
		 * 
		 * Searches for k-gram in the array with specified hash value.
		 * @param $hash Hash value to search for.
		 * @param $kGrams Array with k-grams.
		 * @return $kGram Returns whole k-gram with specified hash.
		 */
		public static function findKGramByHash($hash, $kGrams) {
			foreach ($kGrams as $kGram) {
				if ($kGram[0] == $hash) {
					return $kGram;
				}
			}
			return null;
		}
		
	}	

?>