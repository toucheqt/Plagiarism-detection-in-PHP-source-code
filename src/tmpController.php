<?php

	include 'Tokens.php';
	include 'Metrics.php';
	
	$file = 'HelloWorld';
	
	$tokenizer = new Tokenizer();
	$tokenizer->setFile('./tests/test-files/' . $file . '.php');
	if ($tokenizer->getTokens()) echo 'Successfuly loaded tokens into file ' . $file . ".json\n";
	else {
		echo 'Couldnt save tokens into file ' . $file . ".json\nEnding now...\n";
		exit();
	}
	
	$metrics = new Metrics();
	if ($metrics->setFile('./tokens/' . $file . '.json')) echo 'Successfuly decode ' . $file . ".json\n";
	else {
		echo 'Couldnt decode file ' . $file . ".json\nEnding now...\n";
		exit();
	}
	
	 
?>
