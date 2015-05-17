<?php

	include __DIR__ . '/../entity/Pair.php';

	/**
	 * Utility class containing methods for easy manipulation with arrays.
	 * All methods inside this class are static.
	 * @author Ondrej Krpec, xkrpec01@stud.fit.vutbr.cz
	 *
	 */
	class ArrayUtils {
		
		/**
		 * Generates sequence of unique pairs.
		 * @param assignments Generate all unique pairs of assignment-assignment
		 * @param templates Generate all unique pairs of assignment-template
		 * @return Returns unique matched pairs as an array.
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
					if (strcmp($subItemArray[Constant::PATTERN_DIR], $itemArray[Constant::PATTERN_DIR])) {
						$pair = array();
						$pair[] = $itemArray[Constant::PATTERN_DIR];
						$pair[] = $subItemArray[Constant::PATTERN_DIR];
						$matchedPairs[] = $pair;
					}
				}
				
				// generate unique assignment-template pairs
				if (!is_null($templates)) {
					foreach($templates as $subItem) {
						$subItemArray = (array) $subItem;
						if (strcmp($subItemArray[Constant::PATTERN_DIR], $itemArray[Constant::PATTERN_DIR])) {
							$pair = array();
							$pair[] = $itemArray[Constant::PATTERN_DIR];
							$pair[] = $subItemArray[Constant::PATTERN_DIR] . Constant::PATTERN_TEMPLATE;
							$matchedPairs[] = $pair;
						}
					}
				}
			}
			return $matchedPairs;
		}
		
		/**
		 * 
		 * Finds assignments by their name in the JSON structures that is in environment entity.
		 * @param $firstAssignment Name of the first assignment to look for.
		 * @param $secondAssignment Name of the second assignment to look for.
		 * @param $environment Environment entity containing all the processed assignments.
		 * @throws UnexpectedValueException Throws an exception if any of the assignments are not found.
		 * @return $pair Returns Pair entity if both assignments are found.
		 */
		public static function findAssignmentsByName($firstAssignment, $secondAssignment, $environment) {
			$projects = (object) $environment->getProjects();
			$templates = (object) $environment->getTemplates();

			$pair = new Pair();
			
			// get first assignment
			if (strpos($firstAssignment, Constant::PATTERN_TEMPLATE) !== false) {
				$tmpArray = explode(Constant::PATTERN_TEMPLATE, $firstAssignment, 2);
				@$pair->setFirstAssignment($templates->{$tmpArray[0]});
			} else {
				@$pair->setFirstAssignment($projects->{$firstAssignment});
			}
			
			// get second assignment
			if (strpos($secondAssignment, Constant::PATTERN_TEMPLATE) !== false) {
				$tmpArray = explode(Constant::PATTERN_TEMPLATE, $secondAssignment, 2);
				@$pair->setSecondAssignment($templates->{$tmpArray[0]});
			} else { 
				@$pair->setSecondAssignment($projects->{$secondAssignment});
			}
			
			if (is_null($pair->getFirstAssignment()) || is_null($pair->getSecondAssignment()))
				throw new UnexpectedValueException();
				
			return $pair;
			
		}
		
	}

?>