<?php

	/**
	 * Controller for main workflow of the script. Separated into several phases.
	 * @author Ondrej Krpec, xkrpec01@stud.fit.vutbr.cz
	 */

	include __DIR__ . '/../entity/TokenBlock.php';
	include __DIR__ . '/../entity/Environment.php';
	include __DIR__ . '/../parser/ArgParser.php';
	include __DIR__ . '/../metrics/halstead/Halstead.php';
	include __DIR__ . '/../workers/TokensWorker.php';
	include __DIR__ . '/../workers/DirectoryWorker.php';
	include __DIR__ . '/../workers/Matching.php';
	include __DIR__ . '/../utils/FileUtils.php';
	include __DIR__ . '/../utils/ArrayUtils.php';
	include __DIR__ . '/../utils/Logger.php';	
	include __DIR__ . '/../utils/WorkerUtils.php';
	
	####################################  MAIN WORKFLOW  ################################
	$arguments = getArguments($argc, $argv);
	if (is_null($arguments)) 
		exit();
		
	// print help if needed
	if ($arguments->getIsHelp()) {
		ArgParser::printHelp();
		exit();
	}
	
	$environment = new Environment();
	
	// process phases
	if ($arguments->getIsGlobalFlow() || $arguments->getIsGenerateFiles() || $arguments->getIsStepOne()) {
		$environment = processFirstPhase($arguments);
		if (is_null($environment))
			exit();
	}
	
	if ($arguments->getIsGlobalFlow() || $arguments->getIsGenerateFiles() || $arguments->getIsStepTwo()) {
		$environment = processSecondPhase($arguments, $environment);
		if (is_null($environment))
			exit();
	}
	
	if ($arguments->getIsGlobalFlow() || $arguments->getIsEval() || $arguments->getIsStepThree()) {
		$environment = processThirdPhase($arguments, $environment);
		if (is_null($environment))
			exit();
	}
	
	if ($arguments->getIsGlobalFlow() || $arguments->getIsEval() || $arguments->getIsStepFour()) {
		$environment = processFourthPhase($arguments, $environment);
		if (is_null($environment))
			exit();
	}
		

	#######################################  FUNCTIONS  #################################
	
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
	 * First phase of this tool. Process input files and creates JSON structure from them.
	 * At the end, saves the JSON structure into file for future usage.
	 * @param $arguments Arguments entity containing arguments from command line.
	 * @return $environment  Enviroment entity containing JSON file with projects and templates. 
	 */
	function processFirstPhase($arguments) {
		
		$environment = new Environment();
		
		// set template JSON file if delivered
		if (!is_null($arguments->getTemplateJSON())) {
			try {
				$environment->setTemplates(FileUtils::getJSONFromFile($arguments->getTemplateJSON()));
				Logger::info('Loaded templates from JSON file. ');
			} catch (Exception $ex) {
				Logger::errorFatal('Error during loading template JSON file. ');
				return null;
			}
		} else if (!is_null($arguments->getInputTemplatePath())) {
			$templates = DirectoryWorker::getSubDirectories($arguments->getInputTemplatePath(), $arguments->getIsRemoveComments());
			$environment->setTemplates($templates);
			
			// save templates to JSON
			try {
				FileUtils::saveToJSON($arguments->getOutputPath(), $arguments->getJSONOutputFilename() . '-templates', 
						$environment->getTemplates());
				Logger::info('JSON file with template assignments was successfuly created. ');
			} catch (Exception $ex) {
				Logger::error('Error during saving JSON file with template assignments. ');
			}
		}
		
		// set input JSON file if delivered, otherwise creates it from input path
		if (!is_null($arguments->getInputJSON())) {
			try {
				$environment->setProjects(FileUtils::getJSONFromFile($arguments->getInputJSON()));
				Logger::info('Loaded input JSON file with assignments. ');
			} catch (Exception $ex) {
				Logger::errorFatal('Error during loading input JSON file. ');
				return null;
			}
		} else {
			$projects = DirectoryWorker::getSubDirectories($arguments->getInputPath(), $arguments->getIsRemoveComments());
			$environment->setProjects($projects);
			
			// save JSON
			try {
				FileUtils::saveToJSON($arguments->getOutputPath(), $arguments->getJSONOutputFilename(), $environment->getProjects());
				Logger::info('JSON file with assignments was successfuly created. ');
			} catch (Exception $ex) {
				Logger::error('Error during saving JSON file with assignments. ');
			}
		}
		
		return $environment;
	}
	
	/**
	 * Second phase of the script. Loads processed assignments and generates unique pairs for comparison from them.
	 * At the end, pairs are saved into CSV file.
	 * @param $arguments Arguments entity containing arguments from command line.
	 * @param $environment Enviroment entity that might containt preprocessed assignments from earlier phases.
	 * @return $environment Enviroment entity containing matched pairs and JSON objects.
	 */
	function processSecondPhase($arguments, $environment) {
		
		$environment = WorkerUtils::getJSONByArguments($arguments, $environment);
		$environment->setMatchedPairs(ArrayUtils::getUniquePairs($environment->getProjects(), $environment->getTemplates()));
		
		// export CSV
		try {
			FileUtils::saveToCSV($arguments->getOutputPath(), $arguments->getCSVOutputFilename(), $environment->getMatchedPairs());
			Logger::info('CSV file with unique pairs was successfuly created. ');
		} catch (Exception $ex) {
			Logger::error('Error during saving CSV file. ');
		}

		return $environment;
	}
	
	/**
	 * 
	 * Third phase of the script. Searches for plagiarism using Halstead metrics and Levenshtein algorithm.
	 * At the end, results are saved into CSV file.
	 * @param $arguments Arguments entity containing arguments from command line.
	 * @param $environment Enviroment entity that might containt preprocessed assignments from earlier phases.
	 * @return $environment Environment entity containing evaluated assignments.
	 */
	function processThirdPhase($arguments, $environment) {
		
		// load assignments if previous phases were not done
		$environment = WorkerUtils::getJSONByArguments($arguments, $environment);
		$matching = new Matching();
		$output = array();
	
		if (is_null($environment->getMatchedPairs())) {
			$environment->setMatchedPairs(FileUtils::getFromCSV($arguments->getInputCSV(), $arguments->getStartIndex(), $arguments->getCount()));
		} else if (!$arguments->getIsForce()) { // is force is false, create page 
			$environment->createPage($arguments->getStartIndex(), $arguments->getCount());
		}

		// compare all pairs in page
		$iterator = 0;
		foreach ($environment->getMatchedPairs() as $matchedPair) {
			try {
				$pair = ArrayUtils::findAssignmentsByName($matchedPair[0], $matchedPair[1], $environment);
			} catch (UnexpectedValueException $ex) {
				Logger::error('Could not find projects: ' . $matchedPair[0] . ', ' . $matchedPair[1]);
				continue;
			}	
			$matching->evaluateHalstead($pair->getFirstHalstead(), $pair->getSecondHalstead());
			$matching->evaluateLevenshtein($pair->getFirstLevenshtein(), $pair->getSecondLevenshtein());
			
			// save comparison result
			$tmpArray = array();
			$tmpArray[] = $matchedPair[0];
			$tmpArray[] = $matchedPair[1];
			$tmpArray[] = $matching->getResultDistance() . ' %';
			$tmpArray[] = $matching->getSimilarBlocks();
			
			$output[] = $tmpArray;
			$iterator++;
			if ($iterator % 100 == 0) {
				Logger::info('Evaluated ' . $iterator . ' pairs of assignments. ');
			}
		}
		
		$environment->setShallowOutput($output);
		FileUtils::saveToCSV($arguments->getOutputPath(), $arguments->getCSVOutputFilename() . Constant::PATTERN_SHALLOW_EVAL, 
				$environment->getShallowOutput());
				
		Logger::info('Shallow analysis is completed. ');
				
		return $environment;
	}
	
	/**
	 * 
	 * Fourth phase of the script. Searches for plagiarism using document's fingerprint method and algorithm Winnowing.
	 * At the end, results are saved into CSV file.
	 * @param $arguments Arguments entity containing arguments from command line.
	 * @param $environment entity that might containt preprocessed assignments from earlier phases.
	 */
	function processFourthPhase($arguments, $environment) {
		
		// load assignments if previous phases were not done
		$environment = WorkerUtils::getJSONByArguments($arguments, $environment);
		$matching = new Matching();
		
		if (is_null($environment->getShallowOutput())) {
			$environment->setShallowOutput(FileUtils::getResultsFromCSV($arguments->getInputCSV()));
		} 
		
		// get pairs for depth analysis
		$depthAnalysisPairs = array();
		foreach ($environment->getShallowOutput() as $pair) {
			$percentage = explode(' ', $pair[2], 2);
			if ($percentage[0] >= Constant::LEVENSHTEIN_SIMILARITY_PERCENT || $pair[3] > Constant::LEVENSHTEIN_MAX_BLOCKS) {
				try {
					$foundedPair = ArrayUtils::findAssignmentsByName($pair[0], $pair[1], $environment);
				} catch (UnexpectedValueException $ex) {
					Logger::error('Could not find projects: ' . $pair[0] . ', ' .$pair[1]);
					continue;
				}
				
				$matching->evaluateWinnowing($foundedPair->getFirstAssignment(), $foundedPair->getSecondAssignment());
				$output = array();
				$output[] = $pair[0];
				$output[] = $pair[1];
				$output[] = $pair[2];
				foreach ($matching->getSimilarityHashes() as $hash) {
					$output[] = $hash;
				}
				if (!is_null($matching->getSimilarityHashes()) && count($matching->getSimilarityHashes()) > 0)
					$depthAnalysisPairs[] = $output;
			} 
		}
		
		FileUtils::saveToCSV($arguments->getOutputPath(), $arguments->getCSVOutputFilename() . Constant::PATTERN_DEPTH_EVAL, $depthAnalysisPairs);
		
		Logger::info('Depth analysis is completed. ');
		
	}
	
?>
