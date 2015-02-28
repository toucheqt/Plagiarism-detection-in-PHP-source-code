<?php

	include '../entity/TokenBlock.php';
	include '../parser/JsonConverter.php';
	include '../parser/ArgParser.php';
	include '../metrics/halstead/Halstead.php';
	include '../workers/TokensWorker.php';
	include '../workers/DirectoryWorker.php';
	
	// ====== parse arguments ==========
	$argParser = new ArgParser($argc, $argv);
	$arguments = null;
	try {
		$arguments = $argParser->parseArguments();
	}
	catch (InvalidArgumentException $ex) {
		exit(1);
	}
	
	if ($arguments->getIsHelp()) {
		$argParser->printHelp();
		return;
	}
	
	// ========= get template projects =============
	$templateDirectories = DirectoryWorker::getSubDirectories($arguments->getTemplateDirectory());
	
	
	// todo popredavat argumenty prislusnym fcim

	// ====== get tokens =======
	$filename = "D:\\eclipse-workspace\\php\\Bachelor-Thesis\\tests\\test-files\\All.php";
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
	
?>
