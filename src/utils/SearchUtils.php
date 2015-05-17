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
		
	}	

?>