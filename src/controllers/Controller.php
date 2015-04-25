<?php

	include __DIR__ . '/../entity/TokenBlock.php';
	include __DIR__ . '/../entity/Enviroment.php';
	include __DIR__ . '/../parser/ArgParser.php';
	include __DIR__ . '/../metrics/halstead/Halstead.php';
	include __DIR__ . '/../workers/TokensWorker.php';
	include __DIR__ . '/../workers/DirectoryWorker.php';
	include __DIR__ . '/../workers/Matching.php';
	include __DIR__ . '/../utils/FileUtils.php';
	include __DIR__ . '/../utils/ArrayUtils.php';
	include __DIR__ . '/../utils/Logger.php';	
	include __DIR__ . '/../utils/WorkerUtils.php';

	// phases
	// phase 1 - DONE
	// phase 2 - ze zadaneho JSON project & template souboru vygenerovat dvojice do CSV souboru
	// phase 3 - eval halstead + eval levensthein
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
	
	$enviroment = new Enviroment();
	
	// process phases
	if ($arguments->getIsGlobalFlow() || $arguments->getIsGenerateFiles() || $arguments->getIsStepOne()) {
		$enviroment = processFirstPhase($arguments);
		if (is_null($enviroment))
			exit();
	}
	
	if ($arguments->getIsGlobalFlow() || $arguments->getIsGenerateFiles() || $arguments->getIsStepTwo()) {
		$enviroment = processSecondPhase($arguments, $enviroment);
		if (is_null($enviroment))
			exit();
	}
	
	if ($arguments->getIsGlobalFlow() || $arguments->getIsEval() || $arguments->getIsStepThree()) {
		$enviroment = processThirdPhase($arguments, $enviroment);
		if (is_null($enviroment))
			exit();
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
		} catch (InvalidArgumentException $iae) {
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
				$enviroment->setTemplates(FileUtils::getJSONFromFile($arguments->getTemplateJSON()));
			} catch (Exception $ex) {
				Logger::errorFatal('Error during loading template JSON file. ');
				return null;
			}
		} // FIXME predelat, prvni faze a JSON nejdou dohromady
		
		// set input JSON file if delivered, otherwise creates it from input path
		if (!is_null($arguments->getInputJSON())) {
			try {
				$enviroment->setProjects(FileUtils::getJSONFromFile($arguments->getInputJSON()));
			} catch (Exception $ex) {
				Logger::errorFatal('Error during loading input JSON file. ');
				return null;
			}
		} else {
			$projects = DirectoryWorker::getSubDirectories($arguments->getInputPath(), $arguments->getIsRemoveComments());
			$enviroment->setProjects($projects);
			
			// save JSON
			try {
				FileUtils::saveToJSON($arguments->getOutputPath(), $arguments->getJSONOutputFilename() . Constant::JSON_FILE_EXTENSION,
						$enviroment->getProjects());
				Logger::info('JSON file with assignments was successfuly created. ');
			} catch (Exception $ex) {
				Logger::error('Error during saving JSON file with assignments. ');
			}
		}
		
		return $enviroment;
	}
	
	/**
	 * Generated unique pairs of assignments.
	 * @return Enviroment entity containing matched pairs and JSON objects.
	 */
	function processSecondPhase($arguments, $enviroment) {
		
		$enviroment = WorkerUtils::getJSONByArguments($arguments, $enviroment);
		$matchedPairs = ArrayUtils::getUniquePairs($enviroment->getProjects(), $enviroment->getTemplates());
		$enviroment->setMatchedPairs($matchedPairs);
		
		// export CSV
		try {
			FileUtils::saveToCSV($arguments->getOutputPath(), $arguments->getCSVOutputFilename(), $matchedPairs);
			Logger::info('CSV file with unique pairs was successfuly created. ');
		} catch (Exception $ex) {
			Logger::error('Error during saving CSV file. ');
		}

		return $enviroment;
	}
	
	/**
	 * Compares given sets of assignments and evaluates results.
	 */
	function processThirdPhase($arguments, $enviroment) {
		
		$enviroment = WorkerUtils::getJSONByArguments($arguments, $enviroment);
		$matching = new Matching();

		if (is_null($enviroment->getMatchedPairs()))
			$enviroment->setMatchedPairs(FileUtils::getFromCSV($arguments->getInputCSV(), $arguments->getStartIndex(), $arguments->getCount()));
		else if (!$arguments->getIsForce()) // is force is false, create page
			$enviroment->createPage($arguments->getStartIndex(), $arguments->getCount());
			
		foreach ($enviroment->getMatchedPairs() as $matchedPair) {
			try {
				$pair = ArrayUtils::findAssignmentsByName($matchedPair[0], $matchedPair[1], $enviroment);
			} catch (UnexpectedValueException $ex) {
				Logger::error('Could not find projects: ' . $matchedPair[0] . ', ' . $matchedPair[1]);
				continue;
			}
			echo $matchedPair[0] . " - " . $matchedPair[1] . "\n";
			$matching->evaluateHalstead($pair->getFirstHalstead(), $pair->getSecondHalstead());
			echo "length: " . $matching->getResultProgramLength() . "\nvolume : " . $matching->getResultVolume() . "\ndiff: " . $matching->getResultDifficulty() . "\n\n";
		}
		
		// TODO Continue
		// vytahnout projekty ze CSV - DONE
		// najit prislusne projekty v JSON a ulozit si oba do objektu - DONE
		// porovnat je
	}
	
?>
