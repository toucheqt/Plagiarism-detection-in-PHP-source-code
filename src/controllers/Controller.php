<?php

	include __DIR__ . '/../entity/TokenBlock.php';
	include __DIR__ . '/../entity/Enviroment.php';
	include __DIR__ . '/../parser/ArgParser.php';
	include __DIR__ . '/../metrics/halstead/Halstead.php';
	include __DIR__ . '/../workers/TokensWorker.php';
	include __DIR__ . '/../workers/DirectoryWorker.php';
	include __DIR__ . '/../utils/JsonUtils.php';
	include __DIR__ . '/../utils/Logger.php';	

	// phases
	// phase 1 - vygenerovat json ze zadane slozky s projekty
	// phase 2 - ze zadaneho json project & template souboru vygenerovat dvojice do csv souboru
	// phase 3 - eval halstead +eval levensthein
	// phase 4 - eval winnowing
	
	// ============= main workflow =============
	$arguments = getArguments($argc, $argv);
	if (is_null($arguments)) 
		exit();
		
	// print help if needed
	if ($arguments->getIsHelp()) {
		ArgParser::printHelp();
		exit();
	}
	
	if ($arguments->getIsGlobalFlow() || $arguments->getIsGenerateFiles() || $arguments->getIsStepOne()) {
		$enviroment = processFirstPhase();
	}
		

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
	 * Generates JSON file from assignments.
	 * @return Enviroment entity containing JSON file with projects and templates. 
	 */
	function processFirstPhase($arguments) {
		
		$enviroment = new Enviroment();
		
		// set template JSON file if delivered
		if (!is_null($arguments->getTemplateJSON())) {
			try {
				$enviroment->setTemplate(JsonUtils::getJsonFromFile($arguments->getTemplateJSON()));
			}
			catch (Exception $ex) {
				Logger::errorFatal('Error during loading template JSON file. ');
				return null;
			}
		}
		
		// set input JSON file if delivered, otherwise creates it from input path
		if (!is_null($arguments->getInputJSON())) {
			try {
				$enviroment->setProject(JsonUtils::getJsonFromFile($arguments->getInputJSON()));
			}
			catch (Exception $ex) {
				Logger::errorFatal('Error during loading input JSON file. ');
				return null;
			}
		}
		else {
			$project = DirectoryWorker::getSubDirectories($arguments->getInputPath(), $arguments->getIsRemoveComments());
			$enviroment->setProject($project);
			
			// save json
			try {
				JsonUtils::saveToJson($arguments->getOutputPath(), $arguments->getJsonOutputFilename() . Constant::JSON_FILE_EXTENSION,
						$enviroment->getProject());
				Logger::info('JSON file with assignments was successfuly created. ');
			}
			catch (Exception $ex) {
				Logger::error('Error during saving JSON file with assignments. ');
			}
		}
		
		return $enviroment;
		
	}
	
?>
