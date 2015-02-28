<?php

	/**
	 * 
	 * Enter description here ...
	 * @author Touche
	 *
	 */
	class SearchUtils {
		
		/**
		 * Returns true if array contains given needle.
		 * @param unknown_type $needle
		 * @param unknown_type $array
		 */
		public static function inArray($needle, $array) {
			foreach ($array as $item) {
				if (strcmp($needle, $item))
					return true;
			}
			return false;
		}
		
	}	

?>