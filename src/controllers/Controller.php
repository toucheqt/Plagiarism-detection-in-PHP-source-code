<?php

	include __DIR__ . '/../entity/TokenBlock.php';
	include __DIR__ . '/../parser/ArgParser.php';
	include __DIR__ . '/../metrics/halstead/Halstead.php';
	include __DIR__ . '/../workers/TokensWorker.php';
	include __DIR__ . '/../workers/DirectoryWorker.php';
	include __DIR__ . '/../utils/JsonUtils.php';
	include __DIR__ . '/../utils/Logger.php';
	
	// GLOBAL PROGRAM CONSTANTS
	define('DEFAULT_PATH', './..');
	define('TEMPLATE_PATH', '/templates');
	define('PROJECT_PATH', '/projects');

	
	// get arguments
	$argParser = new ArgParser($argc, $argv);
	try {
		$arguments = $argParser->parseArguments();
	}
	catch (InvalidArgumentException $iae) {
		exit(-1);
	}
	
	if ($arguments->getIsHelp()) {
		$argParser->printHelp();
		return;
	}
	
	// ========= get template projects =============
//	$templateDirectories = DirectoryWorker::getSubDirectories($arguments->getTemplateDirectory());
//	Logger::info("Successfuly encoded templates.");
//	
//	// save templates
//	JsonUtils::saveToJson(DEFAULT_PATH, "template.json", $templateDirectories);
	
	
	// TODO 1. spravne rozdelovat halstead block a levensthein blocky
	// 2. predelat nacitani parametru
	// 3. ulozit template json do souboru
	// 4. zpracovat template json parametr
	// 5. to same udelat pro student projecty
	// 6. zacit je porovnavat
	
	//echo "[INFO] Task finished.\n";
	
	// todo popredavat argumenty prislusnym fcim

	// ====== get tokens =======
	/*$filename = "D:\\eclipse-workspace\\php\\Bachelor-Thesis\\tests\\template";
	try {
		$tokensWorker = new TokensWorker($filename);
		$converter = new JsonConverter();
		$converter->saveToJson("D:\\eclipse-workspace\\php\\Bachelor-Thesis\\tokens\\" , 'All.json', $tokensWorker->getTokens());
	}
	catch (InvalidArgumentException $ex) {
		echo 'Skipping file ' . $filename;
	}
	
	echo "[INFO] Sucessfuly encoded input file to json.\n";
	
	$tokenBlock = new TokenBlock($tokensWorker->getTokens());
	
	echo "[INFO] Sucessfuly processed tokens.\n";
	echo "[INFO] Sucessfuly evaluated halstead metrics.\n";
	echo "[INFO] Sucessfuly parsed levenshtein metrics.\n";
	*/
	
?>
