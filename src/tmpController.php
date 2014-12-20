<?php

	include 'Tokens.php';
	include 'Metrics.php';
	
	$file = 'Function';
	
	$tokenizer = new Tokenizer();
	$tokenizer->setFile('./tests/test-files/' . $file . '.php');
	if ($tokenizer->getTokens()) echo 'Successfuly loaded tokens into file ' . $file . ".json\n";
	else {
		echo 'Couldnt save tokens into file ' . $file . ".json\nEnding now...\n";
		exit();
	}
	
	$metrics = new Metrics();
	if ($metrics->setFile('./tokens/' . $file . '.json')) echo 'Successfuly decoded ' . $file . ".json\n";
	else {
		echo 'Couldnt decode file ' . $file . ".json\nEnding now...\n";
		exit();
	}
	
	echo "\n";
	print_r($metrics->getContent());
	echo "\n";
	
	// print functions count
	$metrics->getMetrics();
	echo 'Number of functions in ' . $file . '.php = ' . $metrics->getFunctionCount() . "\n";
	echo 'Number of global variables in ' . $file . '.php = ' . $metrics->getGlobalVarCount() . "\n";
	echo 'Number of @ in ' . $file . '.php = ' . $metrics->getAtUsageCount() . "\n";
	echo 'Number of eval() in ' . $file . '.php = ' . $metrics->getEvalCount() . "\n";	
	echo 'Number of goto in ' . $file . '.php = ' . $metrics->getGotoCount() . "\n";	
	
	$tmp = $metrics->getHalsteadMetrics();
	
	echo 'Number of operands in first function in ' . $file . '.php = ' . $tmp[0]->getOperandsCount() . "\n";
	echo 'Number of unique operands in first function in ' . $file . '.php = ' . count($tmp[0]->getUniqueOperands()) . "\n";
	echo 'Number of operators in first function in ' . $file . '.php = ' . $tmp[0]->getOperatorsCount() . "\n";
	echo 'Number of unique operators in first function in ' . $file . '.php = ' . count($tmp[0]->getUniqueOperators()) . "\n";
	echo 'Calculated function length = ' . $tmp[0]->getProgramLength() . "\n";
	echo 'Volume = ' . $tmp[0]->getVolume() . "\n";
	echo 'Difficulty = ' . $tmp[0]->getDifficulty() . "\n";
	 
?>
