<?php

	include __DIR__ . '/../entity/TokenBlock.php';
	include __DIR__ . '/../entity/Enviroment.php';
	include __DIR__ . '/../parser/ArgParser.php';
	include __DIR__ . '/../metrics/halstead/Halstead.php';
	include __DIR__ . '/../workers/TokensWorker.php';
	include __DIR__ . '/../workers/DirectoryWorker.php';
	include __DIR__ . '/../utils/JsonUtils.php';
	include __DIR__ . '/../utils/Logger.php';
	
	// GLOBAL PROGRAM CONSTANTS
	define('DEFAULT_PATH', './../');
	define('TEMPLATE_PATH', '/templates/');
	define('PROJECT_PATH', '/projects/');
	
	// TODO 1. spravne rozdelovat halstead block a levensthein blocky
	// 2. predelat nacitani parametru - DONE
	// 3. ulozit template json do souboru - DONE
	// 4. zpracovat template json parametr - DONE
	// 5. to same udelat pro student projecty - DONE
	// 6. predelat halsteada na funkce - DONE
	// x. zapracovat oddelavani komentaru v pripade prepinace -c
	// 7. zacit je porovnavat

	
	// ============= main workflow =============
	$arguments = getArguments($argc, $argv);
	if (is_null($arguments)) 
		exit();
		
	$enviroment = prepareEnviroment($arguments);
	if (is_null($enviroment))
		exit();
		
	
	
	// ======== controller functions =========
	
	/**
	 * 
	 * Loads arguments into argument entity. Returns null if exception occurred.
	 * @param $argc
	 * @param $argv
	 */
	function getArguments($argc, $argv) {
		$argParser = new ArgParser($argc, $argv);
		try {
			$arguments = $argParser->parseArguments();
		}
		catch (InvalidArgumentException $iae) {
			return null;
		}
		
		return $arguments;
	} 
	
	/**
	 * 
	 * Prepares enviroment entity for comparisons.
	 * @param $arguments
	 */
	function prepareEnviroment($arguments) {

		if ($arguments->getIsHelp()) {
			ArgParser::printHelp();
			return null;
		}
		
		$enviroment = new Enviroment();
		
		// get templates
		if (!is_null($arguments->getTemplateJSONPath())) {
			try {
				$enviroment->setTemplate(JsonUtils::getJsonFromFile($arguments->getTemplateJSONPath()));
			}
			catch (Exception $ex) {
				Logger::errorFatal('Problem during loading JSON template.');
				return null;
			} 
			
			Logger::info('Template JSON file was successfuly loaded.');
		}
		else if (!is_null($arguments->getTemplatesPath())) {
			$enviroment->setTemplate(DirectoryWorker::getSubDirectories($arguments->getTemplatesPath(),
					$arguments->getIsRemoveComments()));
			try {
				JsonUtils::saveToJson(DEFAULT_PATH, 'template.json', $enviroment->getTemplate());
			}
			catch (Exception $ex) {
				Logger::errorFatal('Problem during saving JSON template.');
				return null;
			}

			Logger::info('Template JSON file was successfuly created.');
		}
		
		// get projects
		if (!is_null($arguments->getProjectJSONPath())) {
			try {
				$enviroment->setProject(JsonUtils::getJsonFromFile($arguments->getProjectJSONPath()));
			}
			catch (Exception $ex) {
				Logger::errorFatal('Problem during loading JSON project.');
				return null;
			}
			
			Logger::info('Project JSON file was successfuly loaded.');
		}
		else if (!is_null($arguments->getProjectsPath())) {
			$enviroment->setProject(DirectoryWorker::getSubDirectories($arguments->getProjectsPath(),
					$arguments->getIsRemoveComments()));
			try {
				JsonUtils::saveToJson(DEFAULT_PATH, 'projects.json', $enviroment->getProject());
			}
			catch (Exception $ex) {
				Logger::errorFatal('Problem during saving JSON project.');
				return null;
			}
			Logger::info('Project JSON file was successfuly created.');
		}
		else { // should not happen
			Logger::errorFatal('No project path or file was specified.');
			return null;
		}
		
		return $enviroment;
		
	}
	
?>
