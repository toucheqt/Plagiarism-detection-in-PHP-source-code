<?php

	include '../parser/TokensWorker.php';
	include '../parser/JsonConverter.php';
	include '../metrics/halstead/Halstead.php';

	// ====== get tokens =======
	$filename = "D:\\eclipse-workspace\\php\\Bachelor-Thesis\\tests\\test-files\\Halstead.php";
	try {
		$tokensWorker = new TokensWorker($filename);
		$converter = new JsonConverter();
		$converter->saveToJson("D:\\eclipse-workspace\\php\\Bachelor-Thesis\\tokens\\" , 'Halstead.json', $tokensWorker->getTokens());
	}
	catch (InvalidArgumentException $ex) {
		echo 'Skipping file ' . $filename;
	}
	
	echo "[INFO] Sucessfuly encoded input file to json.\n";
	
	$halstead = new Halstead($tokensWorker->getTokens());
	$halstead->processTokens(-1);

?>
