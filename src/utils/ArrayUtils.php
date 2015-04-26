<?php

	include __DIR__ . '/../entity/Pair.php';

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
		
		/**
		 * Returns JSON objects of given assignments.
		 * @throws UnexpectedValueException
		 */
		public static function findAssignmentsByName($firstAssignment, $secondAssignment, $enviroment) {
			$projects = (object) $enviroment->getProjects();
			$templates = (object) $enviroment->getTemplates();

			$pair = new Pair();
			
			// get first assignment
			if (strpos($firstAssignment, '-template') !== false) {
				$tmpArray = explode('-template', $firstAssignment, 2);
				@$pair->setFirstAssignment($templates->{$tmpArray[0]});
			} else {
				@$pair->setFirstAssignment($projects->{$firstAssignment});
			}
			
			// get second assignment
			if (strpos($secondAssignment, '-template') !== false) {
				$tmpArray = explode('-template', $secondAssignment, 2);
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