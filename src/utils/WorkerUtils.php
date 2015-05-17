<?php

	/**
	 * 
	 * Utility class for encapsulating work with other utility classes.
	 * @author Ondrej Krpec, xkrpec01@stud.fit.vutbr.cz
	 *
	 */
	class WorkerUtils {
		
		/**
		 * 
		 * Loads project and template JSON files into environment variable, if they are delivered by arguments.
		 * @param $arguments Arguments parsed from the command line.
		 * @param $environment Environment entity used for saving data needed by the script workflow.
		 * @return $environment Returns updated environment entity with input and template JSON structure.
		 */
		public static function getJSONByArguments($arguments, $environment) {
			// load input JSON file if first phase was not done
			if (is_null($environment->getProjects())) {
				if (is_null($arguments->getInputJSON())) { // should not happen
					Logger::errorFatal('No input files were delivered. ');
					return null;
				}
				
				try {
					$environment->setProjects(FileUtils::getJSONFromFile($arguments->getInputJSON()));
					Logger::info('JSON file with assignments was successfuly loaded. ');
				} catch (Exception $ex) {
					Logger::errorFatal('Error during loading input JSON file. ');
					return null;
				}
			}
			
			// load template JSON file if first phase was not done
			if (is_null($environment->getTemplates()) && !is_null($arguments->getTemplateJSON())) {
				try {
					$environment->setTemplates(FileUtils::getJSONFromFile($arguments->getTemplateJSON()));
					Logger::info('JSON file with templates was successfuly loaded. ');
				} catch (Exception $ex) {
					Logger::errorFatal('Error during loading template JSON file. ');
					return null;
				}
			}
			
			return $environment;
		}
		
	}

?>