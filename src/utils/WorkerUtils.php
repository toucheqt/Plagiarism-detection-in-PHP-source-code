<?php

	/**
	 * 
	 * Utility class for encapsulating work with other utility classes.
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class WorkerUtils {
		
		/**
		 * 
		 * Loads project and template JSON files into enviroment variable, if they are delivered by arguments.
		 * @param unknown_type $arguments
		 * @param unknown_type $enviroment
		 */
		public static function getJSONByArguments($arguments, $enviroment) {
			// load input JSON file if first phase was not done
			if (is_null($enviroment->getProjects())) {
				if (is_null($arguments->getInputJSON())) { // should not happen
					Logger::errorFatal('No input files were delivered. ');
					return null;
				}
				
				try {
					$enviroment->setProjects(FileUtils::getJSONFromFile($arguments->getInputJSON()));
					Logger::info('JSON file with assignments was successfuly loaded. ');
				} catch (Exception $ex) {
					Logger::errorFatal('Error during loading input JSON file. ');
					return null;
				}
			}
			
			// load template JSON file if first phase was not done
			if (is_null($enviroment->getTemplates()) && !is_null($arguments->getTemplateJSON())) {
				try {
					$enviroment->setTemplates(FileUtils::getJSONFromFile($arguments->getTemplateJSON()));
					Logger::info('JSON file with templates was successfuly loaded. ');
				} catch (Exception $ex) {
					Logger::errorFatal('Error during loading template JSON file. ');
					return null;
				} // TODO Save log into file and add timestamp
			}
			
			return $enviroment;
		}
		
	}

?>